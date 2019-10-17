# Husker Scraper

![Husker](https://upload.wikimedia.org/wikipedia/commons/thumb/e/e5/Nebraska_Cornhuskers_logo.svg/1200px-Nebraska_Cornhuskers_logo.svg.png)

## The problem!
_(We need data!!!)_  
Inspired by this twitter discussion:
https://twitter.com/_walkonU/status/1183929054589837312

> @_walkonU
>Bye week.  Open to any research project on the Huskers.  What do you want to know?

> @cuzcastjustin
> How about a chart with the total number of seniors, juniors, sophomores, and freshmen going back the last 30 years?

> @_walkonU
> Yeah. *That would take a lot longer than a week tho*.

> @cuzcastjustin
> I guess i was looking at a total number of players of each class on the roster each year. For instance, @Huskerpod tweeted out the number in each class this year. It was a lot of Fr and RFr, but i was more interested in how the numbers of upperclassmen stacked up to previous yrs

> @_walkonU
> Might be able to use media guide for this.  Does @HailVarsity already have this data?

> @cuzcastjustin
> I know it’s on Huskermax on the depth charts

> @_walkonU
> Just a matter of taking the time and getting what you want manually?

---

## Solution!

This project leverages `paquettg/php-html-parser` to scrape the data from the source suggested by my man @cuzcastjustin.
So I can do you one better than last 30 years and go back to 1962 when the data get's to funky to make exceptions.

* *Here's the 7k line scraped data:*
  [Full Table](sample-output/full-table.txt)
* *And the class (year) table:*
  [Class table](sample-output/class-table.txt)