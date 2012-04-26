<?php
/*
 * Copyright (c) 2011, Josef Kufner  <jk@frozen-doe.net>
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

	protected function setup_form($form, $defaults)
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

	protected function postprocess_data($data)
	{
		if ($this->abort->isSubmittedBy()) {
			$in_vals = $this->collect_numeric_inputs();
			$redirect_url = vsprintf($this->in('no_url'), $in_vals);
			if ($redirect_url != '') {
				$redirect_anchor = vsprintf($this->in('no_anchor'), $in_vals);
				$this->template_option_set('root', 'redirect_url', $redirect_anchor ? $redirect_url.'#'.$redirect_anchor : $redirect_url);
			}
			return false;
		} else {
			return true;
		}
	}
}

