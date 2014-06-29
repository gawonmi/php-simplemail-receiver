<?php
/**
 * MailContainer.php
 * PHP version 5
 *
 * @category Mail
 * @package  EmailConnector\model
 * @author   jlcardosa <joseluis.cardosamanzano@maviance.com>
 * @license  maviance GmbH 2014
 * @version  SVN: $Id$
 * @link     MailContainer
 */
namespace SimpleMailReceiver\Commons;

/**
 * Collection Container
 */
class Collection implements \IteratorAggregate, \Countable
{

    /**
     * Collection
     *
     * @var array
     */
    protected $items;

    /**
     * Constructor of the class
     *
     * @param array $items items array
     */
    public function __construct(array $items = null)
    {
        if (!empty($items)) {
            $this->items = $items;
        }
    }

    /**
     * Add new item to collection
     *
     * @param $item
     */
    public function addItem($item)
    {
        $this->items[ ] = $item;
    }

    /**
     * Get the Item of the collection
     *
     * @param $key
     * @return mixed
     */
    public function getItem($key = null)
    {
        return $key ? $this->items[ $key ] : $this->items;
    }

    /**
     * Get the array Iterator
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return sizeof($this->items);
    }
}
