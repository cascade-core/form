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
 * Show form created by form/form block.
 */


class B_form__show extends Block {

	protected $inputs = array(
		'form' => array(),		// Form from form/form block.
		'template' => 'form/show',	// Template to use.
		'hide' => false,		// Do not show block.
		'slot' => 'default',
		'slot-weight' => 50,
		'*' => null,
	);

	protected $outputs = array(
	);

	const force_exec = true;


	public function main()
	{
		if (!$this->in('hide')) {
			$data = array();
                        foreach ($this->inputNames() as $in) {
                                if (!in_array($in, array('form', 'template', 'hide', 'slot', 'slot-weight', 'enable'))) {
                                        $data[$in] = $this->in($in);
                                }
                        }
                        $data['form'] = $this->in('form');

                        $this->templateAdd(null, $this->in('template'), $data);
		}
	}

}

