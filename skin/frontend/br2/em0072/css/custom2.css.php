<?php
/**
 * EMThemes
 *
 * @license commercial software
 * @copyright (c) 2012 Codespot Software JSC - EMThemes.com. (http://www.emthemes.com)
 */

# Generate background gradient:
# http://www.colorzilla.com/gradient-editor/

$vars = array();

# overwrite style of 'button1' 
// $vars['button1'] = <<<EOB
// 	background: #262626; /* Old browsers */
// 	/* IE9 SVG, needs conditional override of 'filter' to 'none' */
// 	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iIzI2MjYyNiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjUwJSIgc3RvcC1jb2xvcj0iIzU1NWI1YiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjUxJSIgc3RvcC1jb2xvcj0iIzBhMGUwYSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiMwYTA4MDkiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
// 	background: -moz-linear-gradient(top,  #262626 0%, #555b5b 50%, #0a0e0a 51%, #0a0809 100%); /* FF3.6+ */
// 	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#262626), color-stop(50%,#555b5b), color-stop(51%,#0a0e0a), color-stop(100%,#0a0809)); /* Chrome,Safari4+ */
// 	background: -webkit-linear-gradient(top,  #262626 0%,#555b5b 50%,#0a0e0a 51%,#0a0809 100%); /* Chrome10+,Safari5.1+ */
// 	background: -o-linear-gradient(top,  #262626 0%,#555b5b 50%,#0a0e0a 51%,#0a0809 100%); /* Opera 11.10+ */
// 	background: -ms-linear-gradient(top,  #262626 0%,#555b5b 50%,#0a0e0a 51%,#0a0809 100%); /* IE10+ */
// 	background: linear-gradient(to bottom,  #262626 0%,#555b5b 50%,#0a0e0a 51%,#0a0809 100%); /* W3C */
// 	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#262626', endColorstr='#0a0809',GradientType=0 ); /* IE6-8 */
// 	
// 	color:#ffffff;
// 	border-radius:5px;
// 	border:0;
// EOB;

$vars['button1:prefix'] = $vars['button2:prefix'] = $vars['button3:prefix'] = <<<EOB
	background:url(../images/bkg_nav.png) center center repeat-x;
	box-shadow:0 -3px 8px rgba(0,0,0,0.08) inset;
EOB;

return $vars;