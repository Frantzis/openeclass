<?php // $Id$
/*=============================================================================
       	GUnet e-Class 2.0 
        E-learning and Course Management Program  
================================================================================
       	Copyright(c) 2003-2006  Greek Universities Network - GUnet
        A full copyright notice can be read in "/info/copyright.txt".
        
       	Authors:    Costas Tsibanis <k.tsibanis@noc.uoa.gr>
        	    Yannis Exidaridis <jexi@noc.uoa.gr> 
      		    Alexandros Diamantidis <adia@noc.uoa.gr> 

        For a full list of contributors, see "credits.txt".  
     
        This program is a free software under the terms of the GNU 
        (General Public License) as published by the Free Software 
        Foundation. See the GNU License for more details. 
        The full license can be read in "license.txt".
     
       	Contact address: GUnet Asynchronous Teleteaching Group, 
        Network Operations Center, University of Athens, 
        Panepistimiopolis Ilissia, 15784, Athens, Greece
        eMail: eclassadmin@gunet.gr
==============================================================================*/

include('exercise.class.php');
include('question.class.php');
include('answer.class.php');

$require_current_course = TRUE;

include '../../include/baseTheme.php';

$tool_content = "";

$nameTools=$langQuestionPool;
$navigation[]=array("url" => "exercice.php","name" => $langExercices);
$is_allowedToEdit=$is_adminOfCourse;

$TBL_EXERCICE_QUESTION='exercice_question';
$TBL_EXERCICES='exercices';
$TBL_QUESTIONS='questions';
$TBL_REPONSES='reponses';

// maximum number of questions on a same page
$limitQuestPage=50;

if($is_allowedToEdit)
{
	// deletes a question from the data base and all exercises
	if(isset($delete))
	{
		// construction of the Question object
		$objQuestionTmp=new Question();
		
		// if the question exists
		if($objQuestionTmp->read($delete))
		{
			// deletes the question from all exercises
			$objQuestionTmp->delete();
		}

		// destruction of the Question object
		unset($objQuestionTmp);
	}
	// gets an existing question and copies it into a new exercise
	elseif(isset($recup) && $fromExercise)
	{
		// construction of the Question object
		$objQuestionTmp=new Question();

		// if the question exists
		if($objQuestionTmp->read($recup))
		{
/*
					if(!is_object(@$objExercise)) {
						// construction of the Exercise object
						$objExercise=new Exercise();
			
						// creation of a new exercise if wrong or not specified exercise ID
						if(isset($exerciseId)) {
							$objExercise->read($exerciseId);
						}
					
						// saves the object into the session
						session_register('objExercise');
				}			
				$fromExercise=$objExercise->selectId();	
 */
			// adds the exercise ID represented by $fromExercise into the list of exercises for the current question
			$objQuestionTmp->addToList($fromExercise);
		}

		// destruction of the Question object
		unset($objQuestionTmp);
/*

		if(!is_object(@$objExercise)) {
			// construction of the Exercise object
			$objExercise=new Exercise();

			// creation of a new exercise if wrong or not specified exercise ID
			if(isset($exerciseId)) {
				$objExercise->read($exerciseId);
			}	
			// saves the object into the session
			session_register('objExercise');					
		}			
		$exerciseId=$objExercise->selectId();			
*/	
// adds the question ID represented by $recup into the list of questions for the current exercise
		
		$objExercise->addToList($recup);

//		header("Location: admin.php?editQuestion=$recup");
//		exit();
	}
}


// if admin of course
if($is_allowedToEdit)
{

if (isset($fromExercise)) {
	$temp_fromExercise = $fromExercise;
} else {
	$temp_fromExercise = "";
}

	$tool_content .= "
      <div id=\"operations_container\">
        <ul id=\"opslist\">
          <li>";
		  
	if(isset($fromExercise)) {
		$tool_content .= "<a href=\"admin.php\">&lt;&lt; ".$langGoBackToEx."</a>";
	} else {
		$tool_content .= "<a href=\"admin.php?newQuestion=yes\">".$langNewQu."</a>";
	}
	
	$tool_content .= "
          </li>
        </ul>
      </div>";

$tool_content .= <<<cData
\n
    <form method="get" action="${PHP_SELF}">
cData;
    //<!--<input type="hidden" name="fromExercise" value="$temp_fromExercise">-->

$tool_content .= <<<cData


    <table align="center" width="99%" class="Question">
    <thead>
    <tr>
cData;
	
	$tool_content .= "
      <th colspan=\"";
	if (isset($fromExercise))
		$tool_content .= "2";
	else
		$tool_content .= "3";
		
	$tool_content .= "\" align=\"right\" class=\"right\">";
  
	$tool_content .= "<b>".$langFilter."</b>: 
      <select name=\"exerciseId\" class=\"FormData_InputText\">"."
        <option value=\"0\">-- ".$langAllExercises." --</option>"."
        <option value=\"-1\" ";
		
	if(isset($exerciseId) && $exerciseId == -1) 
		$tool_content .= "selected=\"selected\""; 
	$tool_content .= ">-- ".$langOrphanQuestions." --</option>";
	
	if (isset($fromExercise)) {
		mysql_select_db($currentCourseID);
		$sql="SELECT id,titre FROM `$TBL_EXERCICES` WHERE id<>'$fromExercise' ORDER BY id";
		$result=mysql_query($sql) or die("Error : SELECT at line ".__LINE__);
	} else {
		mysql_select_db($currentCourseID);
		$sql="SELECT id,titre FROM `$TBL_EXERCICES` ORDER BY id";
		$result=mysql_query($sql) or die("Error : SELECT at line ".__LINE__);
	}
	
	// shows a list-box allowing to filter questions
	while($row=mysql_fetch_array($result)) {
		$tool_content .= "
        <option value=\"".$row['id']."\"";
	
	if(isset($exerciseId) && $exerciseId == $row['id']) 
		$tool_content .= "selected=\"selected\"";
	$tool_content .= ">".$row['titre']."</option>";
	}
	
$tool_content .= <<<cData

      </select>
      <input type="submit" value="${langOk}">
      </th>
    </tr>
cData;

	@$from=$page*$limitQuestPage;
	
	// if we have selected an exercise in the list-box 'Filter'
	if(isset($exerciseId) && $exerciseId > 0)
	{
		$sql="SELECT id,question,type FROM `$TBL_EXERCICE_QUESTION`,`$TBL_QUESTIONS` WHERE question_id=id AND exercice_id='$exerciseId' ORDER BY q_position LIMIT $from,".($limitQuestPage+1);
		$result=mysql_query($sql) or die("Error : SELECT at line ".__LINE__);
	}
	// if we have selected the option 'Orphan questions' in the list-box 'Filter'
	elseif(isset($exerciseId) && $exerciseId == -1)
	{
		$sql="SELECT id,question,type FROM `$TBL_QUESTIONS` LEFT JOIN `$TBL_EXERCICE_QUESTION` ON question_id=id WHERE exercice_id IS NULL ORDER BY question LIMIT $from,".($limitQuestPage+1);
		$result=mysql_query($sql) or die("Error : SELECT at line ".__LINE__);
	}
	// if we have not selected any option in the list-box 'Filter'
	else
	{		
		@$sql="SELECT id,question,type FROM `$TBL_QUESTIONS` LEFT JOIN `$TBL_EXERCICE_QUESTION` ON question_id=id WHERE exercice_id IS NULL OR exercice_id<>'$fromExercise' GROUP BY id ORDER BY question LIMIT $from,".($limitQuestPage+1);
		$result=mysql_query($sql) or die("Error : SELECT at line ".__LINE__);

		// forces the value to 0
		$exerciseId=0;
	}

	$nbrQuestions=mysql_num_rows($result);

$tool_content .= <<<cData

    <tr>
      <th colspan="
cData;


if (isset($fromExercise))
	$tool_content .= "2";
else
	$tool_content .= "3";

$tool_content .= "\">

      <table width=\"100%\">
      <thead>
      <tr>";

	  $tool_content .= "
        <th align=\"right\">";

	if(isset($page)) {

	$tool_content .= "<small><a href=\"".$PHP_SELF.
		"?exerciseId=".$exerciseId.
		"&fromExercise=".$fromExercise.
		"&page=".($page-1)."\">&lt;&lt; ".$langPrevious."</a></small> |";
	}
	elseif($nbrQuestions > $limitQuestPage)
	{
		$tool_content .= "<small>&lt;&lt; $langPrevious |</small>";
	}

	if($nbrQuestions > $limitQuestPage) {

	$tool_content .= "<small><a href=\"".$PHP_SELF.
	"?exerciseId=".$exerciseId.
	"&fromExercise=".$fromExercise.
	"&page=".($page+1)."\">".$langNext.
	" &gt;&gt;</a></small>";

	}
	elseif(isset($page)) {
		$tool_content .= "<small>$langNext &gt;&gt;</small>";
	}

	 $tool_content .= <<<cData
        </th>
      </tr>
      </thead>
      </table>
	  
      </th>
    </tr>
    <tr>
cData;

	if(isset($fromExercise))
	{

	$tool_content .= <<<cData

      <th class='left' width="90%"><b>${langQuestion}</b></th>
      <th width="10%" align="center"><b>${langReuse}</b></th>
cData;

	}
	else
	{

  $tool_content .= <<<cData
      <th class='left' width="90%"><b>${langQuestion}</b></th>
      <th width="5%" align="center"><b>${langModify}</b></th>
      <th width="5%" align="center"><b>${langDelete}</b></th>
cData;
	}

$tool_content .= "
    </thead>
    <tbody>
    </tr>";
$i=1;

	while($row=mysql_fetch_array($result))
	{
		// if we come from the exercise administration to get a question, doesn't show the question already used by that exercise
		if(!isset($fromExercise) || !is_object(@$objExercise) || !$objExercise->isInList($row['id']))
		{

//$tool_content .= "<tr><td><a href=\"admin.php?editQuestion=".$row['id'].
//	"&fromExercise=".$fromExercise."\">".$row['question']."</a></td><td align=\"center\">";

	if(!isset($fromExercise)) {
	$tool_content .= "
    <tr>
      <td><a href=\"admin.php?editQuestion=".$row['id']."&fromExercise=\"\">".$row['question']."</a></td>
      <td><div align=\"center\"><a href=\"admin.php?editQuestion=".$row['id']."\"><img src=\"../../template/classic/img/edit.gif\" border=\"0\" alt=\"".$langModify."\"></a></div>";
	} else {
	$tool_content .= "
    <tr>
      <td><a href=\"admin.php?editQuestion=".$row['id']."&fromExercise=".$fromExercise."\">".$row['question']."</a></td>
      <td class=\"center\"><div align=\"center\">";
				
	$tool_content .= "<a href=\"".$PHP_SELF."?recup=".$row['id'].
					"&fromExercise=".$fromExercise."\"><img src=\"../../template/classic/img/enroll.gif\" border=\"0\" alt=\"".$langReuse."\"></a>";
	}

  $tool_content .= "
      </td>";

	if(!isset($fromExercise)) {

	  $tool_content .= "
      <td><div align=\"center\"><a href=\"".$PHP_SELF."?exerciseId=".$exerciseId."&delete=".$row['id']."\"". 
		" onclick=\"javascript:if(!confirm('".addslashes(htmlspecialchars($langConfirmYourChoice)).
		"')) return false;\"><img src=\"../../template/classic/img/delete.gif\" border=\"0\" alt=\"".$langDelete."\"></a></div></td>";

			}

$tool_content .= "
    </tr>";

			// skips the last question, that is only used to know if we have or not to create a link "Next page"
			if($i == $limitQuestPage) {
				break;
			}
			$i++;
		}
	}

	if(!$nbrQuestions) {
		$tool_content .= "
    <tr>
      <td colspan=\"";
		if (isset($fromExercise)&&($fromExercise))
			$tool_content .= "2";
		else
			$tool_content .= "3";	
	$tool_content .= "\">".$langNoQuestion."</td>
    </tr>";
}

$tool_content .= <<<cData

    </tbody>
    </table>
</form>
cData;

}
// if not admin of course
else {
	$tool_content .= $langNotAllowed;
}

draw($tool_content, 2, 'exercice');
?>
