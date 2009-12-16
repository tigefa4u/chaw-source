<?php
$this->set('showdown', true);
$script = '
$(document).ready(function(){
	$("#WikiContent").html(converter.makeHtml(jQuery.trim($("#WikiContent").text())));
});
';
$html->scriptBlock($script, array('inline' => false));
?>
<div id="WikiContent" style="width: 50%">
##About Chaw

Chaw is inspired by all the other great apps that came before it, namely Trac and GitHub.
The goal is not to build a clone of these great projects, but to provide an alternative that fits
my vision and allows me to implement the features I need. I hope you find it useful and
maybe even your vision is somehow reflected in the development of Chaw.


###Thank you
- [Trac ](http://trac.edgewell.com) and [GitHub ](http://github.com) for the inspiration
- [Showdown ](http://attacklab.net/showdown/) library for enabling the wiki
- [jQuery ](http://jquery.com) for powering some effects like the live preview
- [Highlight.js](http://softwaremaniacs.org/soft/highlight/en/) for making the source code look pretty
- And of course, [CakePHP ](http://cakephp.org/) and its community for the framework that made development fun

###Feedback would be Chawsome!
 #chaw on irc.freenode.net or join the [google group](http://groups.google.com/group/chaw)
</div>