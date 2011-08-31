{
        "translatorID": "19212b43-f536-4cf9-861a-39a54571d6f2",
        "label": "ScholarPress Shortcodes",
        "creator": "Sean Takats and Jeremy Boggs",
        "target": "",
        "minVersion": "2.1",
        "maxVersion": "",
        "priority": 100,
        "inRepository": "0",
        "translatorType": 2,
        "lastUpdated": "2011-03-13 09:57:34"
}

function doExport() {
	var uriRe = new RegExp('^https?://zotero\.org/(groups|users)/([^/]+)/items/(.+)');
	while(item = Zotero.nextItem()) {
		var uri = item.uri;
		var uriMatch = uriRe.exec(uri);
		var libraryType = uriMatch[1];
		libraryType = libraryType.substring(0, libraryType.length-1)+"_id";
		var libraryID = uriMatch[2];
		var itemKey = uriMatch[3];
		Zotero.write("[zotero "+libraryType+"=\""+libraryID+"\" item_key=\""+itemKey+"\"]\r\n\r\n");
	}
}
