;<?php exit(); __HALT_COMPILER; ?>


[outputs]
done = "1"
title = "Nette Form"

[block:form]
.block = "form/form"
.x = 295
.y = 108
config[] = "load_form:data"

[block:show]
.block = "form/show"
.x = 550
.y = 0
form[] = "form:form"

[block:print_r]
.block = "core/out/print_r"
.x = 551
.y = 168
enable[] = "form:done"
data[] = "form:data"
title = "Submitted data"
slot_weight = "70"

[block:load_form]
.block = "core/ini/load"
.x = 0
.y = 88
filename = "plugin/form/simple_form.example.ini.php"
process_sections = "1"


; vim:filetype=dosini:
