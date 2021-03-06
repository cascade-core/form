<?php
/*
 * Copyright (c) 2010, Josef Kufner  <jk@frozen-doe.net>
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
 * Nette form. Form is created from configuration but this block should be
 * inherited and protected methods reimplemented.
 *
 * See also form/show.
 * See http://doc.nettephp.com/en/nette-forms
 */

class B_form__form extends \Cascade\Core\Block {

	protected $inputs = array(
		'defaults' => null,
		'config' => array(),
		'action' => null,
		'skip_submit' => false,
	);

	protected $outputs = array(
		'form' => true,
		'data' => true,
		'done' => true,
	);


	public final function main()
	{
		/* prepare form */
		$form = new \Nette\Forms\Form($this->id());
		$form->getElementPrototype()->id = $this->fullId();
		if (($action = $this->in('action')) !== null) {
			$form->setAction($action);
		}

		/* setup form */
		$defaults = $this->getDefaults();
		$form = $this->setupForm($form, $defaults);

		$this->out('form', $form);

		$skip_submit = $this->in('skip_submit');

		if ($form->isSubmitted()) {
			/* load data if valid */
			if ($form->isValid()) {
				if ($this->postprocessData($form->getValues())) {
					$this->out('done', true);
				}
			}
		} else {
			/* set defaults if form is not submited */
			if (!empty($defaults)) {
				$form->setDefaults($defaults);
			}

			/* if skip submit, simulate submit */
			if ($skip_submit) {
				if ($form->isValid() && $this->postprocessData($form->getValues())) {
					$this->out('done', true);
				}
			}
		}
	}


	protected function getDefaults()
	{
		return $this->in('defaults');
	}


	protected function setupForm($form, $defaults)
	{
		$factories = array(
			/* type		=>       colon	 factory	*/
			'button'	=> array(false,	'addButton'),
			'checkbox'	=> array(false,	'addCheckbox'),
			'file'		=> array(true,	'addFile'),
			'hidden'	=> array(false,	'addHidden'),
			'html'		=> array(true,	null),
			'image'		=> array(true,	'addImage'),
			'multiselect'	=> array(true,	'addMultiSelect'),
			'password'	=> array(true,	'addPassword'),
			'radiolist'	=> array(false,	'addRadioList'),
			'select'	=> array(true,	'addSelect'),
			'submit'	=> array(false,	'addSubmit'),
			'text'		=> array(true,	'addText'),
			'textarea'	=> array(true,	'addTextArea'),
		);

		$config = $this->in('config');
		$default_required_message = @$config['*']['required-message'];

		/* add form fields */
		foreach ($config as $name => $field) {
			if ($name == '*') {
				continue;	// skip form options
			}
			$type = strtolower($field['type']);

			/* label */
			if ($factories[$type][0]) {
				$label = sprintf('%s:', $field['label']);
			} else {
				$label = $field['label'];
			}

			/* add form field */
			if ($factories[$type][1] !== null) {
				$f = $form->$factories[$type][1]($name, $label);
			} else switch($type) {
				// exceptions here
				default:
					$f = null;
					break;

				case 'html':
					$f = $form->addTextArea($name, $label);
					$f->getControlPrototype()->class('mceEditor');
					break;
			}

			if (!$f) {
				continue;
			}

			/* set default value */
			if (array_key_exists('default', $field)) {
				$f->setDefaultValue($field['default']);
			}

			/* is required? (optionaly set message) */
			if (!empty($field['required'])) {
				$f->setRequired($field['required'] == true
							? sprintf($default_required_message, $label)
							: $field['required']);
			}

			/* is disabled? */
			if (!empty($field['disabled'])) {
				$f->setDisabled();
			}

			/* class? */
			if (!empty($field['class'])) {
				$f->getControlPrototype()->class($field['class']);
			}
		}

		/* TinyMCE before validation */
		$form->getElementPrototype()->onsubmit('tinyMCE.triggerSave()');

		return $form;
	}


	protected function postprocessData($data)
	{
		// Nothing to do here, but you can overload this method and
		// modify submited data before output is set.

		// Set result output
		$this->out('data', $data);

		// Form is valid (output 'done' will be set to true)
		return true;
	}
}

