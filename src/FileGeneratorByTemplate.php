<?php 

namespace Denifelixe\PHPFilesystemHandler;

use Denifelixe\PHPFilesystemHandler\Exceptions\FileAlreadyExistException;

class FileGeneratorByTemplate
{
	protected $destination_folder;

	protected $file_and_class_name;

	protected $file_output_extension;

	protected $template_contents;

	public function setDestinationFolder(string $folder_directory): void
	{
		$folder_directory = dirname($folder_directory . '/.');

		$this->destination_folder = $folder_directory;
	}

	public function setFileAndClassName(string $file_and_class_name): void
	{
		$this->file_and_class_name = $file_and_class_name;
	}

	public function setFileOutputExtension(string $file_output_extension): void
	{
		$this->file_output_extension = $file_output_extension;
	}

	public function setTemplate(string $template_file): void
	{
		$this->template_contents = file_get_contents($template_file);
	}

	public function getFullFilePath()
	{
		return $this->destination_folder . '/' . $this->file_and_class_name . '.' . $this->file_output_extension;
	}

	public function getClassName()
	{
		return basename($this->file_and_class_name);
	}

	public function getNamespace()
	{
		if (dirname($this->file_and_class_name) == '.') {
            $namespace = '';
        } else {
            $namespace = '\\' . str_replace('/', '\\', dirname($this->file_and_class_name));
        }

        return $namespace;
	}

	public function replaceContentInTemplate(string $search, string $replace): void
	{
		$this->template_contents = str_replace($search, $replace, $this->template_contents);
	}

	public function generateFile(): void
	{
		if (file_exists($this->getFullFilePath())) {
            throw new FileAlreadyExistException;
        }

		if (!is_dir(dirname($this->getFullFilePath()))) {
            mkdir(dirname($this->getFullFilePath()), 0777, true);
        }

        file_put_contents($this->getFullFilePath(), $this->template_contents);
	}
}