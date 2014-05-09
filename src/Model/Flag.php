<?php

namespace BPModeration\Model;

use BPModeration\Lib\DAO;

class Flag extends DAO
{
    protected static $table = 'bp_mod_flags';

    protected static $idField = 'flag_id';

    protected static $formats = array(
        'flag_id' => '%d',
        'content_id' => '%d',
        'reporter_id' => '%d',
        'date' => '%s',
    );

    protected $flag_id;
    protected $content_id;
    protected $reporter_id;
    protected $date;

    private $content;
    private $reporter;

    /**
     * @return Content
     */
    public function getContent()
    {
        if (!isset($this->content)) {
            $this->content = Content::findOneByID($this->content_id);
        }
        return $this->content;
    }

    /**
     * @param Content $content
     */
    public function setContent(Content $content)
    {
        $this->content = $content;
        $this->contentID = $content->getContentID();
    }

    public function getReporter()
    {}

    public function setReporter($reporter)
    {}

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $date->setTimezone(new \DateTimeZone('UTC'));
        $this->date = $date->format(self::SQL_DATETIME_FORMAT);
    }

    /**
     * @return \DateTime utc date
     */
    public function getDate()
    {
        return new \DateTime($this->date, new \DateTimeZone('UTC'));
    }
} 