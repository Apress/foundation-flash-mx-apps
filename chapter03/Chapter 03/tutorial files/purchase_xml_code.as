function parsePurchaseXML (success) {

  if(success == false) {
    trace("purchase_history.xml failed to load");
    return;
  }
  
  if (this.status != 0) {
    trace("The XML file was loaded, but was not well formed");
    return;
  }
  
  if (this.firstChild.nodeName.toLowerCase() != "purchasehistory") {
    trace("Unexpected XML data");
    return;
  }
  
  history_arr = this.firstChild.childNodes;
  
  for (var count = 0; count < purchaseHistoryNodes.length; count++) {
    var purchaseID = history_arr[count].attributes.id;
    var purchaseTime = history_arr[count].attributes.time;

    trace("--Purchase " + purchaseID + " @ " + purchaseTime + " -------------");

    parsePurchaseNodes(history_arr[count].childNodes);

    trace("-----------------------------------------");
  }
}


function parsePurchaseNodes(purchase_arr) {

  for (var count = 0; count < purchase_arr.length; count++) {
    var nodeName = purchase_arr[count].nodeName.toLowerCase();

    if(nodeName == "items") {
      parseItemsNode(purchase_arr[count].childNodes);
    } else if (nodeName == "client") {
      parseClientNode(purchase_arr[count].childNodes);
    }
  }
  
}

    
function parseClientNode(client_arr) {

  trace("---- Client Information");
  
  for (var count = 0; count < client_arr.length; count++) {
    trace("        " + client_arr[count].nodeName + ": " + client_arr[count].nodeValue);
  }
  
  trace("---- End Client Information");
}


function parseItemsNode(items_arr) {

  for(var count = 0; count < items_arr.length; count++) {
    trace("---- Item " + items_arr[count].attributes.id);
    parseItemNode(items_arr[count].childNodes);
    trace("---- End Item");
  }
}


function parseItemNode(item_arr) {

  for (var count = 0; count < item_arr.length; count++) {
    trace("        " + item_arr[count].nodeName + ": " + item_arr[count].nodeValue);
  }
}
