=== ScholarPress Researcher ===
Contributors: jeremyboggs, stakats
Tags: researcher, bibliography, Zotero
Requires at least: 2.9.2
Tested up to: 3.3.2
Stable tag: 1.2

ScholarPress researcher uses the Zotero API to display your library in WordPress.

== Installation ==
1. Unzip the download, and copy the 'scholarpress-researcher' plugin into your WordPress plugins folder, normally located in /wp-content/plugins/. In other words, all files for the plugin should be in wp-content/plugins/scholarpress-researcher/

2. Login to Wordpress Admin and activate the plugin.

3. Add shortcodes to pages and posts using the following parameters. Please see http://www.zotero.org/support/dev/server_api/read_api for further documentation.

library_type: "user" or "group" (required)
library_id: the ID associated with the library to be accessed (required)
api_key: the API key required to access private libraries
item_key: the item key if we're accessing a single Zotero item
collection_key: the key of the collection to access if we're pulling a full collection
style: the bibliographic style to use for formatting (default is chicago-note-bibliography)
order: the column order used to return items, e.g. "creator", "dateAdded"
sort: the sort order, "asc" or "desc"
limit: the maximum number of items to retrieve (default is 100)

example: 
[zotero library_id="58212" library_type="group" limit="20" api_key="jnrsdCcH1WJJECn9gNmNC5BL" collection_key="W5TQM8FV" style="apa"]