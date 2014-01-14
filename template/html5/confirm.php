<?php
/*
 * Copyright (c) 2011, Josef Kufner  <jk@frozen-doe.net>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */

function TPL_html5__form__confirm($t, $id, $d, $so)
{
	extract($d);

	$form->render('begin');

	echo "<div class=\"confirm_form\">\n";
	echo "<h3>", htmlspecialchars($form['question']->control->getValue()), "</h3>\n";
	echo "<p>", htmlspecialchars($form['note']->control->getValue()), "</p>\n";


	// Errors
	if ($form->errors) {
		$form->render('errors');
	}

	// Submit
	echo	"<div class=\"submit_area\">\n",
		$form['abort_btn']->control->tabindex(1), "\n",
		$form['submit_btn']->control->tabindex(1), "\n",
		//"<a href=\"/quest/1\" class=\"button bad\" tabindex=\"1\">", _('Cancel'), "</a>\n",
		"</div>\n";

	// End of form
	echo "</div>\n", $form->render('end');
}

