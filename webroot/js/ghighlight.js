/*
Syntax highlighting with language autodetection.
http://softwaremaniacs.org/soft/highlight/

modified 2008-12-12 [gwoo]
*/
var hljs = new function() {

	var DEFAULT_LANGUAGES = ['python', 'ruby', 'perl', 'php', 'css', 'xml', 'html', 'django', 'javascript', 'java', 'cpp', 'cs', 'sql', 'ini', 'diff'];
	var ALL_LANGUAGES = (DEFAULT_LANGUAGES.join(',') + ',' + ['1c', 'axapta', 'delphi', 'rib', 'rsl', 'vbscript', 'profile', 'dos', 'bash', 'lisp', 'smalltalk', 'mel'].join(',')).split(',');
	var LANGUAGE_GROUPS = {
		'xml': 'www',
		'html': 'www',
		'css': 'www',
		'django': 'www',
		'python': 'dynamic',
		'perl': 'dynamic',
		'php': 'dynamic',
		'ruby': 'dynamic',
		'cpp': 'static',
		'java': 'static',
		'delphi': 'static',
		'cs': 'static',
		'rib': 'renderman',
		'rsl': 'renderman'
	}

	var LANGUAGES = {}
	var selected_languages = {};

	function escape(value) {
		return value.replace(/&/gm, '&amp;').replace(/</gm, '&lt;').replace(/>/gm, '&gt;');
	}

	function contains(array, item) {
		if (!array)
			return false;
		for (var i = 0; i < array.length; i++)
			if (array[i] == item)
				return true;
		return false;
	}

	function highlight(language_name, value) {
		function compileSubModes(mode, language) {
			mode.sub_modes = [];
			for (var i = 0; i < mode.contains.length; i++) {
				for (var j = 0; j < language.modes.length; j++) {
					if (language.modes[j].className == mode.contains[i]) {
						mode.sub_modes[mode.sub_modes.length] = language.modes[j];
					}
				}
			}
		}

		function subMode(lexem, mode) {
			if (!mode.contains) {
				return null;
			}
			if (!mode.sub_modes) {
				compileSubModes(mode, language);
			}
			for (var i = 0; i < mode.sub_modes.length; i++) {
				if (mode.sub_modes[i].beginRe.test(lexem)) {
					return mode.sub_modes[i];
				}
			}
			return null;
		}

		function endOfMode(mode_index, lexem) {
			if (modes[mode_index].end && modes[mode_index].endRe.test(lexem))
				return 1;
			if (modes[mode_index].endsWithParent) {
				var level = endOfMode(mode_index - 1, lexem);
				return level ? level + 1 : 0;
			}
			return 0;
		}

		function isIllegal(lexem, mode) {
			return mode.illegalRe && mode.illegalRe.test(lexem);
		}

		function compileTerminators(mode, language) {
			var terminators = [];

			function addTerminator(re) {
				if (!contains(terminators, re)) {
					terminators[terminators.length] = re;
				}
			}

			if (mode.contains)
				for (var i = 0; i < language.modes.length; i++) {
					if (contains(mode.contains, language.modes[i].className)) {
						addTerminator(language.modes[i].begin);
					}
				}

			var index = modes.length - 1;
			do {
				if (modes[index].end) {
					addTerminator(modes[index].end);
				}
				index--;
			} while (modes[index + 1].endsWithParent);

			if (mode.illegal) {
				addTerminator(mode.illegal);
			}

			var terminator_re = '(' + terminators[0];
			for (var i = 0; i < terminators.length; i++)
				terminator_re += '|' + terminators[i];
			terminator_re += ')';
			return langRe(language, terminator_re);
		}

		function eatModeChunk(value, index) {
			var mode = modes[modes.length - 1];
			if (!mode.terminators) {
				mode.terminators = compileTerminators(mode, language);
			}
			value = value.substr(index);
			var match = mode.terminators.exec(value);
			if (!match)
				return [value, '', true];
			if (match.index == 0)
				return ['', match[0], false];
			else
				return [value.substr(0, match.index), match[0], false];
		}

		function keywordMatch(mode, match) {
			var match_str = language.case_insensitive ? match[0].toLowerCase() : match[0]
			for (var className in mode.keywordGroups) {
				if (!mode.keywordGroups.hasOwnProperty(className))
					continue;
				var value = mode.keywordGroups[className].hasOwnProperty(match_str);
				if (value)
					return [className, value];
			}
			return false;
		}

		function processKeywords(buffer, mode) {
			if (!mode.keywords || !mode.lexems)
				return escape(buffer);
			if (!mode.lexemsRe) {
				var lexems_re = '(' + mode.lexems[0];
				for (var i = 1; i < mode.lexems.length; i++)
					lexems_re += '|' + mode.lexems[i];
				lexems_re += ')';
				mode.lexemsRe = langRe(language, lexems_re, true);
			}
			var result = '';
			var last_index = 0;
			mode.lexemsRe.lastIndex = 0;
			var match = mode.lexemsRe.exec(buffer);
			while (match) {
				result += escape(buffer.substr(last_index, match.index - last_index));
				keyword_match = keywordMatch(mode, match);
				if (keyword_match) {
					keyword_count += keyword_match[1];
					result += '<span class="'+ keyword_match[0] +'">' + escape(match[0]) + '</span>';
				} else {
					result += escape(match[0]);
				}
				last_index = mode.lexemsRe.lastIndex;
				match = mode.lexemsRe.exec(buffer);
			}
			result += escape(buffer.substr(last_index, buffer.length - last_index));
			return result;
		}

		function processBuffer(buffer, mode) {
			if (mode.subLanguage && selected_languages[mode.subLanguage]) {
				var result = highlight(mode.subLanguage, buffer);
				keyword_count += result.keyword_count;
				relevance += result.relevance;
				return result.value;
			} else {
				return processKeywords(buffer, mode);
			}
		}

		function startNewMode(mode, lexem) {
			if (mode.returnBegin) {
				result += '<span class="' + mode.className + '">';
				mode.buffer = '';
			} else if (mode.excludeBegin) {
				result += escape(lexem) + '<span class="' + mode.className + '">';
				mode.buffer = '';
			} else {
				result += '<span class="' + mode.className + '">';
				mode.buffer = lexem;
			}
			modes[modes.length] = mode;
		}

		function processModeInfo(buffer, lexem, end) {
			var current_mode = modes[modes.length - 1];
			if (end) {
				result += processBuffer(current_mode.buffer + buffer, current_mode);
				return false;
			}

			var new_mode = subMode(lexem, current_mode);
			if (new_mode) {
				result += processBuffer(current_mode.buffer + buffer, current_mode);
				startNewMode(new_mode, lexem);
				relevance += new_mode.relevance;
				return new_mode.returnBegin;
			}

			var end_level = endOfMode(modes.length - 1, lexem);
			if (end_level) {
				if (current_mode.returnEnd) {
					result += processBuffer(current_mode.buffer + buffer, current_mode) + '</span>';
				} else if (current_mode.excludeEnd) {
					result += processBuffer(current_mode.buffer + buffer, current_mode) + '</span>' + escape(lexem);
				} else {
					result += processBuffer(current_mode.buffer + buffer + lexem, current_mode) + '</span>';
				}
				while (end_level > 1) {
					result += '</span>';
					end_level--;
					modes.length--;
				}
				modes.length--;
				modes[modes.length - 1].buffer = '';
				if (current_mode.starts) {
					for (var i = 0; i < language.modes.length; i++) {
						if (language.modes[i].className == current_mode.starts) {
							startNewMode(language.modes[i], '');
							break;
						}
					}
				}
				return current_mode.returnEnd;
			}

			if (isIllegal(lexem, current_mode))
				throw 'Illegal';
		}

		var language = LANGUAGES[language_name];
		var modes = [language.defaultMode];
		var relevance = 0;
		var keyword_count = 0;
		var result = '';
		try {
			var index = 0;
			language.defaultMode.buffer = '';
			do {
				var mode_info = eatModeChunk(value, index);
				var return_lexem = processModeInfo(mode_info[0], mode_info[1], mode_info[2]);
				index += mode_info[0].length;
				if (!return_lexem) {
					index += mode_info[1].length;
				}
			} while (!mode_info[2]);
			if(modes.length > 1)
				throw 'Illegal';
			return {
				relevance: relevance,
				keyword_count: keyword_count,
				value: result
			}
		} catch (e) {
			if (e == 'Illegal') {
				return {
					relevance: 0,
					keyword_count: 0,
					value: escape(value)
				}
			} else {
				throw e;
			}
		}
	}

	function blockText(block) {
		var result = '';
		for (var i = 0; i < block.childNodes.length; i++)
			if (block.childNodes[i].nodeType == 3)
				result += block.childNodes[i].nodeValue;
			else if (block.childNodes[i].nodeName == 'BR')
				result += '\n';
			else
				throw 'No highlight';
		return result;
	}

	function blockLanguage(block) {
		var classes = block.className.split(/\s+/);
		for (var i = 0; i < classes.length; i++) {
			if (classes[i] == 'no-highlight') {
				throw 'No highlight'
			}
			if (LANGUAGES[classes[i]]) {
				return classes[i];
			}
		}
	}

	function getStartingLineNumber(block) {
		var classes = block.className.split(/\s+/);
		for (var i = 0; i < classes.length; i++) {
			if (classes == 'no-linenumbers') {
				return -1;
			}
			if (classes.match("startAt") != null) {
				//nearest I can tell there are some wierd things happenning with numbers; hence the "/ 1" part
				return classes.match(/\d+/) / 1;
			}
		}
		if(lineNumbers) return 1;
		return -1;
	}

	function getAlternatingRowsOn(block) {
		var classes = block.className.split(/\s+/);
		for (var i = 0; i < classes.length; i++) {
			if (classes == 'no-alternating-rows') {
				return false;
			}
			if (classes == 'alternating-rows') {
				return true;
			}
		}
		return alternatingRows;
	}

	var language = '';

	function highlightBlock(block) {
		try {
			var text = blockText(block);
			language = blockLanguage(block);
		} catch (e) {
			if (e == 'No highlight')
				return;
		}

		if (language) {
			var result = highlight(language, text).value;
		} else {
			var max_relevance = 2;
			var relevance = 0;
			for (var key in selected_languages) {
				if (!selected_languages.hasOwnProperty(key))
					continue;
				var r = highlight(key, text);
				relevance = r.keyword_count + r.relevance;
				if (relevance > max_relevance) {
					max_relevance = relevance;
					var result = r.value;
					language = key;
				}
			}
		}

		if (result) {
			if(startAt != -1 || alternatingRows) {
				result = insertLines(result, startAt, alternatingRows);
			}
			var className = block.className;
			if (!className.match(language)) {
				className += ' ' + language;
			}
			result = '<code class="' + className + '">' + result + '</code>';
			return result;
		}
	}

	function replaceChildOnStage(block, result) {
		// See these 4 lines? This is IE's notion of "block.innerHTML = result". Love this browser :-/
		var container = document.createElement('div');
		container.innerHTML = '<pre>' + result + '</pre>';
		var environment = block.parentNode.parentNode;
		environment.replaceChild(container.firstChild, block.parentNode);
	}
	function rjust(st,siz) {
	t = siz - st.length;
	if (t>0) {st = rspaces(t) + st;}
	return st;}
	function rspaces(len) {
	s = " ";
	for (i=0;i<len;i++) {s = s + " ";}
	return s;}

	function insertLines(highlightedText, startAt, alternatingRows) {
		var counter = startAt + 1 - 1; //make sure it is a number; otherwise Math.log doesn't work right
		var result = "";
		//IE does wierd stuff when splitting blank lines, so insert a space
		highlightedText = highlightedText.replace(/(\r\n|\r|\n)(\r\n|\r|\n)/g, "$1 $2");
		var lines = highlightedText.split(/\r\n|\r|\n/);
		var spaces = Math.ceil(Math.log(lines.length) / Math.log(10));
		var newline = "";
		var line;
		var i;
		var tokenArray = new Array();
		if(spaces < 6)
			spaces = 6;
		for (line = 0; line < lines.length - 1; ++line) {
			newline = "";
			if(alternatingRows) {
				newline = "<span class=\"";
				if(line%2 == 0)
					newline = newline + "coderow";
				else
					newline = newline + "alternaterow";
				newline = newline + "\">";
			}
			if(startAt != -1) {
				var paddingLeft = spaces - (Math.ceil(Math.log(counter + 1) / Math.log(10)));
				newline = newline + "<a href=\"#" + counter + "\" name=\"" + counter + "\" class=\"rownumber\">" + rspaces(paddingLeft) + counter + "</a>";
			}
			for(var restartTokenCt = 0; restartTokenCt < tokenArray.length; ++restartTokenCt) {
				newline = newline + tokenArray[restartTokenCt];
			}
			var tokens = lines[line].match(/(<span class="\w+">)|(<\/span>)|(.*?)/g);
			for (var tokenct = 0;tokenct<tokens.length;++tokenct) {
				if (tokens[tokenct].match(/(<span class="\w+">)/)) {
					tokenArray.push(tokens[tokenct]);
				} else if (tokens[tokenct].match(/(<\/span>)/)) {
					tokenArray.pop();
				}
			}
			newline = newline + lines[line];
			for(var restartTokenCt = 0; restartTokenCt < tokenArray.length; ++restartTokenCt) {
				newline = newline + "</span>";
			}

			newline = newline + "\n";
			if(alternatingRows) {
				newline = newline + "</span>";
			}
			counter++;
			result = result + newline;
		}
		return result + "";
	}

	function langRe(language, value, global) {
		var mode =	'm' + (language.case_insensitive ? 'i' : '') + (global ? 'g' : '');
		return new RegExp(value, mode);
	}

	function compileModes() {
		for (var i in LANGUAGES) {
			if (!LANGUAGES.hasOwnProperty(i))
				continue;
			var language = LANGUAGES[i];
			for (var j = 0; j < language.modes.length; j++) {
				if (language.modes[j].begin)
					language.modes[j].beginRe = langRe(language, '^' + language.modes[j].begin);
				if (language.modes[j].end)
					language.modes[j].endRe = langRe(language, '^' + language.modes[j].end);
				if (language.modes[j].illegal)
					language.modes[j].illegalRe = langRe(language, '^(?:' + language.modes[j].illegal + ')');
				language.defaultMode.illegalRe = langRe(language, '^(?:' + language.defaultMode.illegal + ')');
				if (language.modes[j].relevance == undefined) {
					language.modes[j].relevance = 1;
				}
			}
		}
	}

	function compileKeywords() {

		function compileModeKeywords(mode) {
			if (!mode.keywordGroups) {
				for (var key in mode.keywords) {
					if (!mode.keywords.hasOwnProperty(key))
						continue;
					if (mode.keywords[key] instanceof Object)
						mode.keywordGroups = mode.keywords;
					else
						mode.keywordGroups = {'keyword': mode.keywords};
					break;
				}
			}
		}

		for (var i in LANGUAGES) {
			if (!LANGUAGES.hasOwnProperty(i))
				continue;
			var language = LANGUAGES[i];
			compileModeKeywords(language.defaultMode);
			for (var j = 0; j < language.modes.length; j++) {
				compileModeKeywords(language.modes[j]);
			}
		}
	}

	function findCode(pre) {
		for (var i = 0; i < pre.childNodes.length; i++) {
			node = pre.childNodes[i];
			if (node.nodeName == 'CODE')
				return node;
			if (!(node.nodeType == 3 && node.nodeValue.match(/\s+/)))
				return null;
		}
	}

	function initHighlighting() {
		if (initHighlighting.called)
			return;
		initHighlighting.called = true;
		compileModes();
		compileKeywords();
		if (arguments.length) {
			for (var i = 0; i < arguments.length; i++) {
				if (LANGUAGES[arguments[i]]) {
					selected_languages[arguments[i]] = LANGUAGES[arguments[i]];
				}
			}
		} else
			selected_languages = LANGUAGES;
	}

	function injectScripts(languages) {
		var scripts = document.getElementsByTagName('SCRIPT');
		for (var i = 0; i < scripts.length; i++) {
			if (scripts[i].src.match(/ghighlight\.js(\?.+)?$/)) {
				var path = scripts[i].src.replace(/ghighlight\.js(\?.+)?$/, '');
				break;
			}
		}
		if (languages.length == 0) {
			languages = DEFAULT_LANGUAGES;
		}
		var injected = {}
		for (var i = 0; i < languages.length; i++) {
			var filename = LANGUAGE_GROUPS[languages[i]] ? LANGUAGE_GROUPS[languages[i]] : languages[i];
			if (!injected[filename]) {
				document.write('<script type="text/javascript" src="' + path + 'languages/' + filename + '.js"></script>');
				injected[filename] = true;
			}
		}
	}

	function initHighlightingOnLoad() {
		var original_arguments = arguments;
		//injectScripts(arguments);
		var handler = function(){
			initHighlighting.apply(null, original_arguments)
		};
		if (window.addEventListener) {
			window.addEventListener('DOMContentLoaded', handler, false);
			window.addEventListener('load', handler, false);
		} else if (window.attachEvent)
			window.attachEvent('onload', handler);
		else
			window.onload = handler;
	}

	this.LANGUAGES = LANGUAGES;
	this.ALL_LANGUAGES = ALL_LANGUAGES;
	this.initHighlightingOnLoad = initHighlightingOnLoad;
	this.initHighlighting = initHighlighting;
	this.highlightBlock = highlightBlock;
	this.findCode = findCode;
	this.injectScripts = injectScripts;


	var lineNumbers = false; var startAt = -1;
	this.noLineNumbers = function() { startAt = -1; lineNumbers = false; }
	this.addLineNumbers = function() { startAt = 1; lineNumbers = true; }

	var alternatingRows = false;
	this.noAlternatingRows = function() { alternatingRows=false; }
	this.addAlternatingRows = function() { alternatingRows=true; }

	// Common regexps
	this.IDENT_RE = '[a-zA-Z][a-zA-Z0-9_]*';
	this.UNDERSCORE_IDENT_RE = '[a-zA-Z_][a-zA-Z0-9_]*';
	this.NUMBER_RE = '\\b\\d+(\\.\\d+)?';
	this.C_NUMBER_RE = '\\b(0x[A-Za-z0-9]+|\\d+(\\.\\d+)?)';

	// Common modes
	this.APOS_STRING_MODE = {
		className: 'string',
		begin: '\'', end: '\'',
		illegal: '\\n',
		contains: ['escape'],
		relevance: 0
	};
	this.QUOTE_STRING_MODE = {
		className: 'string',
		begin: '"', end: '"',
		illegal: '\\n',
		contains: ['escape'],
		relevance: 0
	};
	this.BACKSLASH_ESCAPE = {
		className: 'escape',
		begin: '\\\\.', end: '^',
		relevance: 0
	};
	this.C_LINE_COMMENT_MODE = {
		className: 'comment',
		begin: '//', end: '$',
		relevance: 0
	};
	this.C_BLOCK_COMMENT_MODE = {
		className: 'comment',
		begin: '/\\*', end: '\\*/'
	};
	this.HASH_COMMENT_MODE = {
		className: 'comment',
		begin: '#', end: '$'
	};
	this.C_NUMBER_MODE = {
		className: 'number',
		begin: this.C_NUMBER_RE, end: '^',
		relevance: 0
	};
}();

var initHighlightingOnLoad = hljs.initHighlightingOnLoad;


hljs.initHighlightingOnLoad();
/**
 * Chawsomeness
 *
 */
$(document).ready(function(){

	$("pre > code").parent().before("<span class=\"plain\"><a href=\"#plain\">plain</a></span>"
		+ " | <span class=\"highlight\"><a href=\"#highlight\">highlight</a></span>"
		+ " | <span class=\"numbers\"><a href=\"#numbers\">line numbers</a></span>");

	if (location.hash == '#highlight') {
		$('body').append('<div id="curtain">Can make pretty teh code?</div>');
		setTimeout(function() {
			hljs.noAlternatingRows();
			hljs.noLineNumbers();
			$(".highlighted").each(function(i) {
				$(this).html(hljs.highlightBlock(code[i]));
			});
			$('#curtain').fadeOut(3, function() {
				$(this).remove();
			});
		}, 0);
	} else if (location.hash) {
		$('body').append('<div id="curtain">Can make teh pretty code?</div>');
		setTimeout(function() {
			hljs.addAlternatingRows();
			hljs.addLineNumbers();
			$(".highlighted").each(function(i) {
				$(this).html(hljs.highlightBlock(code[i]));
			});
			$('#curtain').fadeOut(3, function() {
				$(this).remove();
			});

			var line = location.hash.slice(1) - 4;
			target = $("a[name=" + line +"]");
			if (target.length) {
				$("html,body").animate({scrollTop: target.offset().top}, 1000);
			}
		}, 0);
	}


	var code = [];
	$("pre").each(function(i) {
		$(this).addClass("highlighted");
		code.push(hljs.findCode(this));
	});

	$("span.plain > a").bind("click", function() {
		$('body').append('<div id="curtain">Speedy Gonzales..</div>');
		setTimeout(function() {
			$(".highlighted").each(function(i) {
				$(this).html("<code>" + $(code[i]).html() + "</code>");
			});
			$('#curtain').fadeOut(3, function() {
				$(this).remove();
			});
		}, 0);
	});

	$("span.highlight > a").bind("click", function() {
		$('body').append('<div id="curtain">Time for a break? This could take a while...</div>');
		setTimeout(function() {
			hljs.noAlternatingRows();
			hljs.noLineNumbers();
			$(".highlighted").each(function(i) {
				$(this).html(hljs.highlightBlock(code[i]));
			});
			$('#curtain').fadeOut(3, function() {
				$(this).remove();
			});
		}, 0);
	});

	$(".numbers a").bind("click", function() {
		$('body').append('<div id="curtain">Grab some coffee? This could take a while...</div</div>');
		setTimeout(function() {
			hljs.addAlternatingRows();
			hljs.addLineNumbers();
			$(".highlighted").each(function(i) {
				$(this).html(hljs.highlightBlock(code[i]));
			});
			$('#curtain').fadeOut(3, function() {
				$(this).remove();
			});
		}, 0);
	});
});
