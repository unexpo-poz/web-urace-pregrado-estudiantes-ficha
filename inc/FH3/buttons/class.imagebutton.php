<?php

/**
 * class ImageButton
 *
 * Create a image button on the given form
 *
 * @author Teye Heimans
 * @package FormHandler
 * @subpackage Buttons
 */
class ImageButton extends Button
{
	var $_bDisableOnSubmit;
    var $_sImage;

    /**
     * ImageButton::ImageButton()
     *
     * Constructor: Create a new ImageButton object
     *
     * @param object $form: the form where the image button is located on
     * @param string $name: the name of the button
     * @param string $image: the image we have to use as button
     * @return ImageButton
     * @access public
     * @author Teye Heimans
     */
    function ImageButton( &$oForm, $sName, $sImage)
    {
        $this->Button($oForm, $sName);

        $this->disableOnSubmit( FH_DEFAULT_DISABLE_SUBMIT_BTN );

        // set the image we use
        $this->_sImage = $sImage;
    }

    /**
     * ImageButton::disableOnSubmit()
     *
     * Set if the imagebutton has to be disabled after pressing it
     * (avoid dubble post!)
     *
     * @param boolean status
     * @return void
     * @access public
     * @author Teye Heimans
     */
    function disableOnSubmit( $bStatus )
    {
        $this->_bDisableOnSubmit = (bool) $bStatus;
    }

    /**
     * ImageButton::getButton()
     *
     * Return the HTML of the button
     *
     * @return string: the HTML of the button
     * @access public
     * @author Teye Heimans
     */
    function getButton()
    {
    	// set the javascript disable dubble submit option if wanted
        if($this->_bDisableOnSubmit)
        {
            // check if there is already an onclick event
            if(isset( $this->_sExtra ) && preg_match("/onclick *= *('|\")(.*)$/i", $this->_sExtra))
            {
                // put the function into a onchange tag if set
                $this->_sExtra = preg_replace("/onclick *= *('|\")(.*)$/i", "onclick=\\1this.form.submit();this.disabled=true;\\2", $this->_sExtra);
            }
            // no onlcick event found, just add it
            else
            {
                $this->_sExtra = "onclick=\"this.form.submit();this.disabled=true;\" ".$this->_sExtra;
            }
        }

        // return the button
        return sprintf(
          '<input type="image" src="%s" name="%s" id="%2$s"%s />',
          $this->_sImage,
          $this->_sName,
          (isset($this->_sExtra) ? ' '.$this->_sExtra:'').
          (isset($this->_iTabIndex) ? ' tabindex="'.$this->_iTabIndex.'"' : '')
        );
    }
}

?>