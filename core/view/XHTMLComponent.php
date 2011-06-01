<?php
interface XHTMLComponent {
	public function getIdName();
	public function inject($mixedContent);
	public function ensamble();
	public function toXhtml();
}
?>