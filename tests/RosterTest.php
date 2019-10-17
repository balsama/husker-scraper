<?php


namespace Balsama\HuskerScraper;

use PHPUnit\Framework\TestCase;

class RosterTest extends TestCase
{
    /**
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    public function testRoster() {
        $year = 1987;
        $roster = new Roster(1987);
        // Just asserting that the constructor worked.
        $this->assertEquals($year, $roster->getYear());

        $array = $roster->getRosterArray();
        // The 1987 Huskers had 103 people on their roster.
        $this->assertEquals(103, count($array));

        $table = $roster->getRosterTable();
        $this->assertStringContainsString('┌────────┬────────────────────┬──────────┬────────┬────────┬───────┬──────────────────┬────────┬─────────────────────┐', $table);

        $abridged_array = $roster->getRosterArray(['height', 'year']);

        $abridged_table = $roster->getRosterTable(['year']);
        // The needle is a table heading with a single column.
        $this->assertStringContainsString('┌───────┐', $abridged_table);

        $year_count = $roster->getClassCountByYear();
    }
}