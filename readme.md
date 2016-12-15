# Dynamic Linker Module

This module has functions and templating inserts for commonly changed and enivornment based links. It's designed to work with minimal setup, only initial installation into a sleepy site and filling out a JSON array for basic usage. There is also a redirect feature which takes a little more work than that, refer to the Redirects section.

## Use cases

**PDFs**: PDFs often have a lot of links and  also get updated somewhat regularly. Some times the name of a PDF has to be updated whenever the PDF itself is, leading common site wide PDF link updates. By using a (site wide) variable to each PDF, renames require one file to be updated.

If PDFs get referenced outside the site (e.g emails) the basic installation wont help much, renaming the PDF will still result in a broken link. However, there is a redirect module for that puprose, which creates a custom URL that always points to whatever PDF is latest.

**Domain Specific Links**: Sometimes links need to change between development, staging, and live. One common case would be links between HCP & Patient sites, where reviewers need to be able to switch between them correctly, especially if both are in development concurrently.

**Mobile Specific PDFs**: Mobile devices can't handle all the features of PDFs, such as interactivity, so often it is necessary to have mobile specific PDFs. By adding `"mobile-href"` to links.json the module will automatically detect if the device is mobile and download that link instead.

## Requirements

* [mobile detection module](https://github.com/sleepymustache/module-mobile-detection)

## How to

For basic usage, just install the module then fill out `links.json` with your files and links. Refer to the code below for visual examples.

	//links.json
	{
	  // only if using a redirect, then generated URL of links will change
	  "redirect": "downloads",

	  "sister-site": {
	    'ENV' : {
	      "DEV": "sister-site.local",
	      "STAGE": "stage.example.com",
	      "LIVE": "example.com",
	    }
	  },
	 "pi" : {
	 	"href": "/downloads/gk-1770-pi.pdf",
	 	"mobile-href": "/downloads/flat-gk-1770-pi.pdf"
	 },

	 [...]
	}

	// In template somewhere...

	<!-- domain specific link -->
	<p>
	 <?php Dyli::get('sister-site', '/some/path', 'link text', 'classy-class') ?>
	</p>


	<!-- link to PDF -->
	<a href="**{[href:pi]}**">another way to link</a>

### Redirects

Redirects are a way to have a consistent URL for links which are renamed often, such as PDF with approval codes in filenames. This will prevent emails from having broken or outdated links to documents. This affects how links display.

###To setup,

First define a redirect location such as `downloads/index.php`. Dynamic linker will be sending requests to that page with query strings.

Second, copy `example-index.php` and move it to that redirect location. Rename to index.php, and then strip away the commenting within the file leaving the code. It should have 1 require statement and an if block.

Finally, define that redirect within links.json. For our example that would be `"redirect": "downloads"`.

After that, you're good to go. Links (which aren't environment specific) will be rerouted through that downloads/index.php file. For the downloads example and a pi the generated link would look like `/downloads/?id=pi`.

If there are links you don't want renamed, use the `"direct" : true` in the links.jon entry for it.

	// links.json

	[...]

	"pi" : {
		"redirect": false,
		"href": "/downloads/gk-1770-pi.pdf"
	}

	[...]
	`
