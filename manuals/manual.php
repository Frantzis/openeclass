<?
/*========================================================================
*   Open eClass 2.3
*   E-learning and Course Management System
* ========================================================================
*  Copyright(c) 2003-2010  Greek Universities Network - GUnet
*  A full copyright notice can be read in "/info/copyright.txt".
*
*  Developers Group:	Costas Tsibanis <k.tsibanis@noc.uoa.gr>
*			Yannis Exidaridis <jexi@noc.uoa.gr>
*			Alexandros Diamantidis <adia@noc.uoa.gr>
*			Tilemachos Raptis <traptis@noc.uoa.gr>
*
*  For a full list of contributors, see "credits.txt".
*
*  Open eClass is an open platform distributed in the hope that it will
*  be useful (without any warranty), under the terms of the GNU (General
*  Public License) as published by the Free Software Foundation.
*  The full license can be read in "/info/license/license_gpl.txt".
*
*  Contact address: 	GUnet Asynchronous eLearning Group,
*  			Network Operations Center, University of Athens,
*  			Panepistimiopolis Ilissia, 15784, Athens, Greece
*  			eMail: info@openeclass.org
* =========================================================================*/

/*
 * eClass manuals Component
 *
 * @author Evelthon Prodromou <eprodromou@upnet.gr>
 * @version $Id$
 *
 * @abstract This component offers  the platform's manuals.
 *
 */

$path2add=2;
include '../include/baseTheme.php';
$nameTools = $langManuals;

$tool_content = "";
$urlServerTemp = strrev(substr(strrev($urlServer),1));

$ext = langname_to_code($language);


function manlink($basename, $langext, $desc)
{
        global $urlServerTemp, $langFormatPDF;

        if (file_exists($basename . '_' . $langext . '.pdf')) {
                $url = $urlServerTemp . '/manuals/' . $basename . '_' . $langext . '.pdf';
        } else {
                $url = $urlServerTemp . '/manuals/' . $basename . '_en.pdf';
        }
        return "<td width='5'><a href='$url' target='_blank' class='mainpage'><img src='../images/pdf.gif' title='$langFormatPDF' alt='$langFormatPDF' /></a></td><td><a href='$url' target='_blank' class='mainpage'>$desc</a></td>";
}

$tool_content .= "
<p>$langIntroMan</p>
<br />

<table style=\"border: 1px solid #edecdf;\">
<thead>
<tr>
  <td>

  <table class=\"FormData\" width='500'>
  <tbody>
  <tr>
        <th colspan='2' class='left'>$langAllTutorials</th>
  </tr>
  <tr>
    ". manlink('OpeneClass22', $ext, $langFinalDesc) ."
  </tr>
  <tr>
    ". manlink('OpeneClass22_short', $ext, $langShortDesc) ."
  </tr>
  <tr>
        <th colspan='2' class='left'>$langTutorials $langOfTeacher</th>
  </tr>
  <tr class=\"odd\">
    ". manlink('OpeneClass22_ManT', $ext, $langManT) ."
  </tr>
  <tr class='odd'>
   <td>&nbsp;</td>
   <td class='left'><strong>$langCreateAccount</strong></td>
  </tr>";

if (isset($language) and $language == 'greek') {
	$tool_content .= "<tr>
	<td>&nbsp;</td>
	<td><img style='vertical-align: bottom;' src='../template/classic/img/pdf.gif' width='20' height='20'>
		<a href='http://www.openeclass.org/guides/pdf/create_teacher_account.pdf' target='_blank'>$langTut</a>
		<strong>|</strong>
		<img src='../template/classic/img/film.png' width='16' height='16'> 
		<a href='http://www.openeclass.org/guides/video/create_teacher_account' target='_blank'>$langScormVideo</a> 
		<strong>| <img src='../template/classic/img/scorm.png' width='16' height='16'></strong> 
		<a href='http://www.openeclass.org/guides/scorm/create_teacher_account.zip'>Scorm Package</a>
	</td>
	</tr>
	<tr class='odd'>
	<td>&nbsp;</td>
	<td class='left'><strong>$langCourseCreate</strong></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td><img style='vertical-align: bottom;' src='../template/classic/img/pdf.gif' width='20' height='20'>
		<a href='http://www.openeclass.org/guides/pdf/create_course.pdf' target='_blank'>$langTut</a>
		<strong>|</strong>
		<img src='../template/classic/img/film.png' width='16' height='16'> 
		<a href='http://www.openeclass.org/guides/video/create_course' target='_blank'>$langScormVideo</a> 
		<strong>| <img src='../template/classic/img/scorm.png' width='16' height='16'></strong> 
		<a href='http://www.openeclass.org/guides/scorm/create_course.zip'>Scorm Package</a>
	</td>
	</tr>
	<tr class='odd'>
	<td>&nbsp;</td>
	<td class='left'><strong>$langPersonalisedBriefcase</strong></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td><img style='vertical-align: bottom;' src='../template/classic/img/pdf.gif' width='20' height='20'>
		<a href='http://www.openeclass.org/guides/pdf/teacher_portfolio.pdf' target='_blank'>$langTut</a>
		<strong>|</strong>
		<img src='../template/classic/img/film.png' width='16' height='16'>
		<a href='http://www.openeclass.org/guides/video/teacher_portfolio' target='_blank'>$langScormVideo</a>
		<strong>| <img src='../template/classic/img/scorm.png' width='16' height='16'></strong>
		<a href='http://www.openeclass.org/guides/scorm/teacher_portfolio.zip'>Scorm Package</a>
	</td>
	</tr>
	<tr class='odd'>
	<td>&nbsp;</td>
	<td class='left'><strong>$langAdministratorCourse</strong></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td><img style='vertical-align: bottom;' src='../template/classic/img/pdf.gif' width='20' height='20'>
		<a href='http://www.openeclass.org/guides/pdf/manage_course.pdf' target='_blank'>$langTut</a>
		<strong>|</strong>
		<img src='../template/classic/img/film.png' width='16' height='16'>
		<a href='http://www.openeclass.org/guides/video/manage_course' target='_blank'>$langScormVideo</a>
		<strong>| <img src='../template/classic/img/scorm.png' width='16' height='16'></strong>
		<a href='http://www.openeclass.org/guides/scorm/manage_course.zip'>Scorm Package</a>
	</td>
	</tr>
	<tr>
		<th colspan='2' class='left'>$langTutorials $langOfStudent</th>
	</tr>";
}
$tool_content .= "<tr class=\"odd\">
    ". manlink('OpeneClass22_ManS', $ext, $langManS) ."
  </tr>";

if (isset($language) and $language == 'greek') {
	$tool_content .= "<tr class=\"odd\">
	<td>&nbsp;</td>
	<td><strong>$langRegCourses</strong></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td><img style='vertical-align: bottom;' src='../template/classic/img/pdf.gif' width='20' height='20'>
		<a href='http://www.openeclass.org/guides/pdf/course_registration.pdf' target='_blank'>$langTut</a>
		<strong>|</strong>
		<img src='../template/classic/img/film.png' width='16' height='16'> 
		<a href='http://www.openeclass.org/guides/video/course_registration' target='_blank'>$langScormVideo</a> 
		<strong>| <img src='../template/classic/img/scorm.png' width='16' height='16'></strong> 
		<a href='http://www.openeclass.org/guides/scorm/course_registration.zip'>Scorm Package</a></td></tr>
	<tr>
	<td>&nbsp;</td>
	<td><strong>$langPersonalisedBriefcase</strong></td></tr>
	<tr><td>&nbsp;</td><td><img style='vertical-align: bottom;' src='../template/classic/img/pdf.gif' width='20' height='20'>
		<a href='http://www.openeclass.org/guides/pdf/student_portfolio.pdf' target='_blank'>$langTut</a>
		<strong>|</strong>
		<img src='../template/classic/img/film.png' width='16' height='16'> 
		<a href='http://www.openeclass.org/guides/video/student_portfolio' target='_blank'>$langScormVideo</a> 
		<strong>| <img src='../template/classic/img/scorm.png' width='16' height='16'></strong> 
		<a href='http://www.openeclass.org/guides/scorm/student_portfolio.zip'>Scorm Package</a></td></tr>
	<tr>
	<td>&nbsp;</td>
	<td><strong>$langIntroToCourse</strong></td></tr>
	<tr class='odd'><td>&nbsp;</td><td><img style='vertical-align: bottom;' src='../template/classic/img/pdf.gif' width='20' height='20'>
		<a href='http://www.openeclass.org/guides/pdf/view_course.pdf' target='_blank'>$langTut</a>
		<strong>|</strong>
		<img src='../template/classic/img/film.png' width='16' height='16'> 
		<a href='http://www.openeclass.org/guides/video/view_course' target='_blank'>$langScormVideo</a> 
		<strong>| <img src='../template/classic/img/scorm.png' width='16' height='16'></strong> 
		<a href='http://www.openeclass.org/guides/scorm/view_course.zip'>Scorm Package</a></td></tr>
	</tbody>
	</table>
	</td>
	</tr>";
}
$tool_content .= "</thead></table><br />";

$tool_content .= "<p><b>$langNote: </b><br/>$langAcrobat <img src='../images/acrobat.png' width='15' height='15' /> $langWhere <a href='http://www.adobe.com/products/acrobat/readstep2.html' target='_blank'><span class='explanationtext'>$langHere</span></a>.</p>";

if (isset($uid) and $uid) {
        draw($tool_content, 1);
} else {
        draw($tool_content, 0);
}

