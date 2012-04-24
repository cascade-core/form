<?php
/*
 * Copyright (c) 2010, Josef Kufner  <jk@frozen-doe.net>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. Neither the name of the author nor the names of its contributors
 *    may be used to endorse or promote products derived from this software
 *    without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE REGENTS AND CONTRIBUTORS ``AS IS'' AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED.  IN NO EVENT SHALL THE REGENTS OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
 * OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 */

/*
 * See http://doc.nettephp.com/en/nette-forms
 *
 */

class M_form__form extends Module {

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
		$form = new NForm($this->id());
		$form->getElementPrototype()->id = $this->full_id();
		if (($action = $this->in('action')) !== null) {
			$form->setAction($action);
		}

		/* setup form */
		$defaults = $this->get_defaults();
		$form = $this->setup_form($form, $defaults);

		$this->out('form', $form);

		$skip_submit = $this->in('skip_submit');

		if ($form->isSubmitted()) {
			/* load data if valid */
			if ($form->isValid()) {
				if ($this->postprocess_data($form->getValues())) {
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
				if ($form->isValid() && $this->postprocess_data($form->getValues())) {
					$this->out('done', true);
				}
			}
		}
	}


	protected function get_defaults()
	{
		return $this->in('defaults');
	}


	protected function setup_form($form, $defaults)
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


	protected function postprocess_data($data)
	{
		// Nothing to do here, but you can overload this method and
		// modify submited data before output is set.

		// Set result output
		$this->out('data', $data);

		// Form is valid (output 'done' will be set to true)
		return true;
	}
}

