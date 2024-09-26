<?php
namespace shicz\Idcard;

class Identity
{
    protected $identityCardNumber;

    public function __construct($identityCardNumber)
    {
        $this->identityCardNumber = str_replace([' ', '-', '_'], '', strtoupper($identityCardNumber));
    }

    /**
     * 身份证检测
     * @author shicz
     * Date:2024/9/26
     */
    public function legal()
    {
        $regionCode = (int)substr($this->identityCardNumber, 0, 6);

        return ($regionCode >= 110000 && $regionCode <= 820000 && checkdate(intval(substr($this->identityCardNumber, 10, 2)), intval(substr($this->identityCardNumber, 12, 2)), intval(substr($this->identityCardNumber, 6, 4))) && $this->validateCheckCode());
    }

    /**
     * 出生年月日
     * @author shicz
     * Date:2024/9/26
     */
    public function birthday()
    {
        $year = substr($this->identityCardNumber, 6, 4);
        $month = substr($this->identityCardNumber, 10, 2);
        $day = substr($this->identityCardNumber, 12, 2);

        return sprintf('%s-%s-%s', $year, $month, $day);
    }

    /**
     * 性别
     * @author shicz
     * Date:2024/9/26
     */
    public function gender()
    {
        return ((intval(substr($this->identityCardNumber, 16, 1)) % 2) === 0) ? '女' : '男';
    }

    /**
     * Get Region of The ID Card People.
     */
    public function region()
    {
        $regionCode = (int)substr($this->identityCardNumber, 0, 6);

        return new Region($regionCode);
    }

    public function validateCheckCode()
    {
        // Init
        $identityCardNumber = $this->identityCardNumber;
        $index = $sum = 0;

        // Calculation $sum
        for ($index; $index < 17; $index++) {
            $sum += ((1 << (17 - $index)) % 11) * intval(substr($identityCardNumber, $index, 1));
        }

        // Calculation $quotiety
        $quotiety = (12 - ($sum % 11)) % 11;

        if ($quotiety < 10) {
            return intval(substr($identityCardNumber, 17, 1)) === $quotiety;
        }

        return $quotiety === 10;
    }


}
