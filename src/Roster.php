<?php

namespace Balsama\HuskerScraper;

use http\Exception\InvalidArgumentException;
use PHPHtmlParser\Dom;
use MathieuViossat\Util\ArrayToTextTable;

class Roster
{
    /**
     * The contents of the roster page for the Huskers in the year provided to the constructor.
     * E.g. https://www.huskermax.com/rosters/1987-roster
     *
     * @var object PHPHtmlParser\Dom
     */
    private $roster_dom;

    private $roster_html;

    private $year;

    private $rows = [];

    /**
     * Roster constructor.
     * @param $year
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    public function __construct($year)
    {
        $this->year = $year;
        $this->roster_dom = new Dom;
        $this->roster_dom->loadFromUrl("https://www.huskermax.com/rosters/$year-roster");
        $this->roster_html = $this->roster_dom->outerHtml;
        $this->isolateRosterFromDom();
    }

    protected function isolateRosterFromDom() {
        $table = $this->roster_dom->find('#roster-table');
        $table_body = $table->find('tbody');

        $html_table_rows = $table_body->find('tr');
        foreach ($html_table_rows as $html_table_row) {
            $this->rows[] = $this->convertHtmlRowToArray($html_table_row);
        }
    }

    /**
     * @param $html_table_row Dom
     * @return array
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
     */
    protected function convertHtmlRowToArray($html_table_row) {
        $processed_row = [
            'number' => $this->processNumber($html_table_row->find('.rost-num')->innerHtml),
            'name' => $html_table_row->find('.rost-name span')->innerHtml,
            'position' => $html_table_row->find('.rost-pos')->innerHtml,
            'weight' => $html_table_row->find('.rost-wt')->innerHtml,
            'year' => $this->processYear($html_table_row->find('.rost-yr')->innerHtml),
            'city' => $html_table_row->find('.rost-city')->innerHtml,
            'state' => $html_table_row->find('.rost-state')->innerHtml,
            'high_school' => $html_table_row->find('.rost-school')->innerHtml,
        ];
        return $processed_row;
    }

    protected function processNumber($num) {
        if (substr($num, 0, 5 ) === "<span") {
            return 000;
        }
        return $num;
    }

    /**
     * @param $dom_height Dom
     * @return string
     */
    protected function processHeight($dom_height) {
        $height = str_replace('<span style="display:none;">', '', $dom_height);
        $height = str_replace('</span>', '', $height);
        // Some instances have curly quotes that need to be removed.
        $height = $this->removeEverythingBeforeChar($height);
        return $height;
    }

    protected function removeEverythingBeforeChar($string, $char = ';') {
        $string = strstr($string, $char);
        if (strpos($string, $char) !== false) {
            $string = ltrim($string, ';');
            self::removeEverythingBeforeChar($string);
        }
        if (strlen($string > 6)) {
            return 'unk';
        }
        return $string;
    }

    /**
     * @param $year_inner_html
     * @return false|string
     */
    function processYear($year_inner_html) {
        $theirYearString = substr($year_inner_html, strpos($year_inner_html, "</span>") + 7);
        if (!in_array(strtolower($theirYearString), ['fr.', 'so.', 'jr.', 'sr.', 'rfr.'])) {
            return 'other';
        }
        return $theirYearString;
    }

    /**
     * @param $year_int
     * @return string
     */
    protected function processYearLegacy($year_int) {
        switch ((int) $year_int) {
            case 1:
                return 'freshman';
            case 3:
                return 'sophomore';
            case 5:
                return 'junior';
            case 7:
                return 'senior';
        }
        return 'unknown year';
    }

    public function getRosterArray($columns = null) {
        if (!$columns) {
            return $this->rows;
        }
        $this->validateColumns($columns);

        $abridged_rows = [];
        foreach ($this->rows as $row) {
            $abridged_rows[] = array_intersect_key($row, array_flip($columns));
        }
        return $abridged_rows;
    }

    public function getClassCountByYear() {
        $year_array = $this->getRosterArray(['year']);
        foreach ($year_array as $item) {
            $year[] = $item['year'];
        }

        $values = array_count_values($year);
        $values = ['year' => $this->year] + $values;

        return $values;
    }

    /**
     * @param $columns array
     */
    protected function validateColumns($columns_requested) {
        $valid_column_names = [
            'number',
            'name',
            'position',
            'weight',
            'year',
            'city',
            'state',
            'high_school',
        ];
        if (array_diff($columns_requested, $valid_column_names)) {
            throw new InvalidArgumentException('Columns must be an array of valid column names.');
        }
    }

    public function getRosterTable($columns = null) {
        $data = $this->getRosterArray($columns);
        $renderer = new ArrayToTextTable($data);
        return $renderer->getTable();
    }

    public function getYear() {
        return $this->year;
    }

}