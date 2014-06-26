<?php
/**
 * MailAttachment.php
 *
 * PHP version 5
 *
 * @category Mail
 * @package  EmailConnector\model\mail
 * @author   jlcardosa <joseluis.cardosamanzano@maviance.com>
 * @license  maviance GmbH 2014
 * @version  SVN: $Id$
 * @link     MailAttachment
 */
namespace EmailConnector\model\mail;

/**
 * Class that represent a file that is attached with the mail
 *
 * @category Mail
 * @package  EmailConnector\model\mail
 * @author   jlcardosa <joseluis.cardosamanzano@maviance.com>
 * @license  maviance GmbH 2014
 * @version  Release: 1.01
 * @link     MailAttachment
 *
 */
class Attachment
{
    /**
     * Name of the file
     *
     * @var string
     */
    private $name;

    /**
     * Kind of file
     *
     * @var string
     */
    private $extension;

    /**
     * Content of the file
     *
     * @var string
     */
    private $content;

    /**
     * Size of the file
     *
     * @var string
     */
    private $size;

    /**
     * Constructor of the class
     *
     * @param string $name      The name
     * @param string $content   The content
     * @param string $extension The extension
     * @param string $size      The size
     */
    public function __construct(
        $name,
        $content,
        $extension = null,
        $size = null
    ) {
        $this->name      = $name;
        $this->content   = $content;
        $this->extension = $extension;
        $this->size      = $size;
    }

    /**
     * Get the name
     *
     * @return the string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name
     *
     * @param string $name The name of the file
     *
     * @return MailAttachment
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the extension
     *
     * @return the string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set the extension
     *
     * @param string $extension The extension
     *
     * @return MailAttachment
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * Get the content
     *
     * @return the string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the content
     *
     * @param string $content The content
     *
     * @return MailAttachment
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get the size
     *
     * @return the string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set the size
     *
     * @param string $size The size
     *
     * @return MailAttachment
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Search in the filename
     *
     * @param string $pattern The pattern to search
     *
     * @return boolean
     */
    public function searchFilename($pattern)
    {
        if (is_int(strpos($this->__toString(), $pattern)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Search in the content of the attachment
     *
     * @param string $pattern The pattern to search
     *
     * @return boolean
     */
    public function searchContent($pattern)
    {
        if (is_int(strpos($this->content, $pattern)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Convert the properties to array
     *
     * @return multitype:NULL
     */
    public function toArray()
    {
        return array(
            "name"      => $this->name,
            "extension" => $this->extension,
            "content"   => $this->content,
            "size"      => $this->size
        );
    }

    /**
     * Convert the object to a string with the following format:
     * filename.extension
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name . '.' . $this->extension;
    }
}
