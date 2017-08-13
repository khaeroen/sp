<?php namespace MAILCLIENT_PLUGIN_NAME;
function mailclient_install() {
}

function mailclient_remove() {
/* Deletes the database field */
	delete_option('mailclient');
}
