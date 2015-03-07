<?php 

use Homesteader\Config\HomesteadConfig;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ConfigShowCommandTest extends \PHPUnit_Framework_TestCase {
	
	public function it_receives_custom_config_file_path_and_parses_the_file_to_array_from_yaml() {}
	public function it_automatically_finds_config_file_path_and_parses_the_file_to_array_from_yaml() {}
	public function it_finds_config_file_path_and_returns_contents_as_string() {}
	public function it_finds_config_file_path_and_returns_contents_as_string_when_class_object_cast_to_string() {}
	public function it_cant_find_custom_config_file_path_file_and_throws_exception() {}
	public function it_cant_find_automatically_found_config_file_and_throws_exception() {}
	public function it_cant_open_config_file_and_throws_exception() {}

	public function it_adds_config_item_to_valid_top_level_item_key() {}
	public function it_attempts_to_add_config_item_to_invalid_top_level_key_and_throws_exception() {}
	public function it_attempts_to_add_empty_config_item_to_valid_top_level_key_and_throws_exception() {}

	public function it_converts_config_to_yaml_and_saves_it_to_config_file_path() {}
	public function it_converts_config_to_yaml_and_attempts_to_save_it_to_config_file_path_but_fails_and_throws_exception() {}

}