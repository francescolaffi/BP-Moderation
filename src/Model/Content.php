<?php


namespace BPModeration\Model;

use BPModeration\Lib\DAO;

class Content extends DAO
{
    protected static $table = 'bp_mod_contents';

    protected static $idField = 'content_id';

    protected static $formats = array(
        'content_id' => '%d',
        'item_type' => '%s',
        'item_id' => '%d',
        'item_id2' => '%d',
        'item_author' => '%d',
        'item_url' => '%s',
        'item_date' => '%s',
        'status' => '%s',
    );

    protected $content_id;
    protected $item_type;
    protected $item_id;
    protected $item_id2;
    protected $item_author;
    protected $item_url;
    protected $item_date;
    protected $status;

    public function getItemID()
    {
        return array($this->item_type, $this->item_id, $this->item_id2);
    }

    public function setItemID(array $compositeID)
    {
        list($this->item_type, $this->item_id, $this->item_id2) = $compositeID;
    }

    public function getAuthorID()
    {
        return $this->item_author;
    }

    public function setAuthorID ($authorID)
    {
        $this->item_author = $authorID;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $date->setTimezone(new \DateTimeZone('UTC'));
        $this->item_date = $date->format(self::SQL_DATETIME_FORMAT);
    }

    /**
     * @return \DateTime utc date
     */
    public function getDate()
    {
        return new \DateTime($this->item_date, new \DateTimeZone('UTC'));
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
} 