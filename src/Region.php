<?php
namespace idcard;

class Region
{
    protected static $regions = [];
    protected $code;

    public function __construct($regionCode)
    {
        if (empty(static::$regions)) {
            static::$regions = json_decode(file_get_contents('region.json'), true);
        }
        $this->code = (string) $regionCode;
    }

    /**
     * 省
     * @author shicz
     * Date:2024/9/26
     */
    public function province()
    {
        return static::$regions[$this->province_code()];
    }

    /**
     * 省-区域号
     * @author shicz
     * Date:2024/9/26
     */
    public function province_code()
    {
        return substr($this->code, 0, 2).'0000';
    }

    /**
     * 市
     * @author shicz
     * Date:2024/9/26
     */
    public function city()
    {
        $cityCode = $this->city_code();
        if (isset(static::$regions[$cityCode])) {
            return static::$regions[$cityCode];
        }
        return '';
    }

    /**
     * 市-区域号
     * @author shicz
     * Date:2024/9/26
     */
    public function city_code()
    {
        return substr($this->code, 0, 4).'00';
    }

    /**
     * 区县
     * @author shicz
     * Date:2024/9/26
     */
    public function county()
    {
        return static::$regions[$this->county_code()];
    }

    /**
     * 区县-区域号
     * @author shicz
     * Date:2024/9/26
     */
    public function county_code()
    {
        return $this->code;
    }

    /**
     * Get The Region Tree.
     * 
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function tree()
    {
        return array_values(array_filter([
            $this->province(),
            $this->city(),
            $this->county(),
        ]));
    }

    /**
     * Get The Region Tree String.
     * 
     * @param string $glue Join Array Elements With A Glue String
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function treeString($glue = '')
    {
        return implode($glue, $this->tree());
    }
}
