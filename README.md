# Title-Key-URL Generator
Generate a title, sort key, and URL from a given set of webpage information, like Title, Subtitle, Description, and Tags.

## What Problems Does This Solve?
* **List Title Generation:** When an item is listed in a list, it should have this List Title, i.e., "The World" should be "World, The", so that all the "The..." titled items do not clump up together.
* **Sort Key Generation:** When an item is listed in a list, it should be sorted by this value, i.e., '"Hello World": Your First Program', should not sort at the end or beginning of a list just because it starts with a quote, but with the other H-titled items.  This value is not intended for display, just for sorting.
* **URL Generation:** Generate a perfectly encoded URL given input like Title, Subtitle, etc., so that '"Hello World": Your First Program' should become "hello-world-your-first-program", which you can then use to name your PHP/HTML/etc. file, or use as an internal code.

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
