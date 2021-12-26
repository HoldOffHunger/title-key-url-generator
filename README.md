# Title-Key-URL Generator
Generate a title, sort key, and URL from a given set of webpage information, like Title, Subtitle, Description, and Tags.

## What Problems Does This Solve?
* **List Title Generation:** When an item is listed in a list, it should have this List Title, i.e., "The World" should be "World, The", so that all the "The..." titled items do not clump up together.
* **Sort Key Generation:** When an item is listed in a list, it should be sorted by this value, i.e., '"Hello World": Your First Program', should not sort at the end or beginning of a list just because it starts with a quote, but with the other H-titled items.  This value is not intended for display, just for sorting.
* **URL Generation:** Generate a perfectly encoded URL given input like Title, Subtitle, etc., so that '"Hello World": Your First Program' should become "hello-world-your-first-program", which you can then use to name your PHP/HTML/etc. file, or use as an internal code.

(See more below in edge-cases handled.)

## Example Usage

	$generator = new TitleKeyURLGenerator();
	
	print_r($generator->GenerateAllValuesForEntry([
		'entry'=>[
			'Title'=>'The Best Blog In the World',
			'Subtitle'=>'An "Inside" Look in 2000',
			'Description'=>'',
			'tags'=>[
				'awesome',
				'best',
				'viral',
			],
		],
	]));
  
  Output:
  
  	Array
	(
	    [url] => the-best-blog-in-the-world-an-inside-look-in-2000-awesome-best-viral
	    [ListTitle] => Best Blog In the World, The: "Inside" Look in 2000, An
	    [ListTitleSortKey] => Best Blog In the World, The: Inside Look in 0000002000, An
	)

## Demo

***See a full working Demo Online Here:*** [Full Working Demo](https://3v4l.org/MgLEG)

## Edge Cases Handled

* URL Generation

  * Replace all white-space with a single dash.
  * Strip commas.
  * Do not repeat any dashes more than once.
  * Do not allow dashes to begin or end the URL.
  * Lowercase everything.
  * Trans-Romanize text, i.e., "Ōsugi Sakae" becomes "Osugi Sakae".

* List Title Generation

  * Break up the title by the ":", "-", and "--" characters, and if any piece begins with "A", "An", or "The", remove that piece and prepend it to the end with a comma.  I.E. "A Way Cool Story: The Details" becomes "Way Cool Story, A: Details, The".

* List Title Key Generation

  * Remove all apostrophes and quotes, so that quoted text and non-quoted text sort accordingly.
  * Left string-pad all numbers with "0" up to ten characters, so that dates sort properly, i.e., 53 AD, 500 AD, and 1900 AD would sort incorrectly otherwise.
  * Trans-Romanize text, i.e., "Ōsugi Sakae" becomes "Osugi Sakae".
