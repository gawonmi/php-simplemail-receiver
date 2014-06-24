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
class Collection implements \IteratorAggregate
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
     * @param $item
     */
    public function addItem($item)
    {
        $this->items[ ] = $item;
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
}
