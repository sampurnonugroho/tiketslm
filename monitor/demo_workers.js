//simple XHR request in pure JavaScript
function load(url, callback) {
	var xhr;

	if(typeof XMLHttpRequest !== 'undefined') xhr = new XMLHttpRequest();
	else {
		var versions = ["MSXML2.XmlHttp.5.0", 
			 	"MSXML2.XmlHttp.4.0",
			 	"MSXML2.XmlHttp.3.0", 
			 	"MSXML2.XmlHttp.2.0",
			 	"Microsoft.XmlHttp"]

		for(var i = 0, len = versions.length; i < len; i++) {
		try {
			xhr = new ActiveXObject(versions[i]);
			break;
		}
			catch(e){}
		} // end for
	}
		
	xhr.onreadystatechange = ensureReadiness;
		
	function ensureReadiness() {
		if(xhr.readyState < 4) {
			return;
		}
			
		if(xhr.status !== 200) {
			return;
		}

		// all is well	
		if(xhr.readyState === 4) {
			callback(xhr);
		}			
	}
		
	xhr.open('GET', url, true);
	xhr.send('');
}
	
//and here is how you use it to load a json file with ajax
load('data.json', function(xhr) {	
	var result = xhr.responseText;	
});

// self.addEventListener('message', function(e) {
  // self.postMessage(e.data);
// }, false);
var result = {'count': 'start', 'msg': ''};
var i = 0;
function timedCount() {
	i = i + 1;
	result.count = i;
	postMessage(result);
	
	setTimeout("timedCount()",1000);
}

var page = 0
function submit(page, callback) {
	var res;
	if(page==0) {
		load('http://pt-bijak.co.id/rest_api_dev_minggu/server/api/table/index2', function(xhr) {	
			res = xhr.responseText;	
			callback(res)
		});
	} else {
		load('http://pt-bijak.co.id/rest_api_dev_minggu/server/api/table/index2?page='+page, function(xhr) {	
			res = xhr.responseText;	
			callback(res)
		});
	}
}

function continues() {
	var prev_page = page;
	submit(prev_page, function(res) {
		if(JSON.parse(res).result=="success") {
			new_page = JSON.parse(res).page;
			if(new_page!=="done") {
				// result.msg = JSON.parse(res).page;
				result.msg = page+" "+prev_page+" "+new_page;
				result.asd = JSON.parse(res).tes;
				postMessage(result);
				
				setTimeout("continues()",1000);
				page = page + 1;
			} else {
				result.msg = "terminated";
				postMessage(result);
			}
		}
	});
	
}

self.addEventListener('message', function(e) {
	var data = e.data;
	switch (data.cmd) {
		case 'start':
			load('http://pt-bijak.co.id/rest_api_dev_minggu/server/api/table/index2', function(xhr) {	
				var res = xhr.responseText;	
				result.msg = res;
				postMessage(result);
			});
			
			continues();
			timedCount(); 
		break;
		case 'stop':
			self.postMessage(data.msg);
		break;
		default:
			self.postMessage('Unknown command: ' + data.msg);
	};
}, false);