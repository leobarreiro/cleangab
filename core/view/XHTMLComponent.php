<?php
interface XHTMLComponent {
	public function getIdName();
	public function inject($mixedContent);
	public function assemble();
	public function toXhtml();
}
?>