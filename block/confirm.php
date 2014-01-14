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

/**
 * Simple confirmation form. Question with yes and no buttons.
 */

class B_form__confirm extends B_form__form {

	protected $inputs = array(
		'question' => null,
		'note' => null,
		'yes_label' => null,
		'yes_class' => null,
		'no_label' => null,
		'no_class' => null,
		'no_url' => null,
		'no_anchor' => null,
		'action' => null,
		'skip_submit' => false,
		'*' => null,
	);

	protected $outputs = array(
		'form' => true,
		'done' => true,
	);

	private $abort;

	protected function setupForm($form, $defaults)
	{
		$form->addHidden('question', $this->in('question'));
		$form->addHidden('note', $this->in('note'));

		$yes_label = $this->in('yes_label');
		$no_label = $this->in('no_label');
		
		$submit = $form->addSubmit('submit_btn', $yes_label === null ? _('Yes') : $yes_label);
		$submit->getControlPrototype()->addClass($this->in('yes_class'));

		$this->abort = $form->addSubmit('abort_btn', $no_label === null ? _('No') : $no_label);
		$this->abort->getControlPrototype()->addClass($this->in('no_class'));

		return $form;
	}

	protected function postprocessData($data)
	{
		if ($this->abort->isSubmittedBy()) {
			$in_vals = $this->collectNumericInputs();
			$redirect_url = vsprintf($this->in('no_url'), $in_vals);
			if ($redirect_url != '') {
				$redirect_anchor = vsprintf($this->in('no_anchor'), $in_vals);
				$this->templateOptionSet('root', 'redirect_url', $redirect_anchor ? $redirect_url.'#'.$redirect_anchor : $redirect_url);
			}
			return false;
		} else {
			return true;
		}
	}
}

