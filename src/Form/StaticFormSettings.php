<?php

/**
* Contains \Drupal\fblikebutton\Form\StaticFormSettings
*/

namespace Drupal\fblikebutton\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class StaticFormSettings extends ConfigFormBase {

  /**
  * {@inhertidoc}
  */
  public function getFormId() {
    return 'fblikebutton_static';
  }

  /**
  * {@inheritdoc}
  */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('fblikebutton.static_settings');

    // Assign global $base_url as default value for block_value settings
    global $base_url;
    if(!$config->get('block_url')) {
      $config->set('block_url', $base_url);
    }

    $form['fblikebutton_static_block_description'] = array(
      '#markup' => '<p>' . $this->t('Set the static URL to like with the button. This Like button will like the given URL, no matter on which page it is displayed. To position this block go the ' . \Drupal::l($this->t('block page'), Url::fromRoute('block.admin_display')) . '.</p>'),
    );
    $form['fblikebutton_static_block_settings'] = array(
      '#type' => 'details',
      '#title' => $this->t('Button settings'),
      '#open' => TRUE,
    );
    $form['fblikebutton_static_block_settings']['fblikebutton_block_url'] = array(
      '#type' => 'textfield',
      '#default_value' => $config->get('block_url'),
      '#description' => $this->t('URL of the page to like (could be your homepage or a facebook page e.g.)')
    );
    $form['fblikebutton_static_block_appearance'] = array(
      '#type' => 'details',
      '#title' => $this->t('Button appearance'),
      '#open' => TRUE,
    );
    $form['fblikebutton_static_block_appearance']['fblikebutton_layout'] = array(
      '#type' => 'select',
      '#title' => $this->t('Layout style'),
      '#options' => array('standard' => $this->t('Standard'),
                          'box_count' => $this->t('Box Count'),
                          'button_count' => $this->t('Button Count')),
      '#default_value' => $config->get('layout'),
      '#description' => $this->t('Determines the size and amount of social context next to the button'),
    );
    // The actial values passed in from the options will be converted to a boolean
    // in the validation function, so it doesn't really matter what we use.
    $form['fblikebutton_static_block_appearance']['fblikebutton_show_faces'] = array(
      '#type' => 'select',
      '#title' => $this->t('Display faces in the box'),
      '#options' => array(TRUE => $this->t('Show faces'), FALSE => $this->t('Do not show faces')),
      '#default_value' => $config->get('show_faces'),
      '#description' => $this->t('Show profile pictures below the button. Only works with Standard layout'),
    );
    $form['fblikebutton_static_block_appearance']['fblikebutton_action'] = array(
      '#type' => 'select',
      '#title' => $this->t('Verb to display'),
      '#options' => array('like' => $this->t('Like'), 'recommend' => $this->t('Recommend')),
      '#default_value' => $config->get('action'),
      '#description' => $this->t('The verb to display in the button.'),
    );
    $form['fblikebutton_static_block_appearance']['fblikebutton_font'] = array(
      '#type' => 'select',
      '#title' => $this->t('Font'),
      '#options' => array(
        'arial' => 'Arial',
        'lucida+grande' => 'Lucida Grande',
        'segoe+ui' => 'Segoe UI',
        'tahoma' => 'Tahoma',
        'trebuchet+ms' => 'Trebuchet MS',
        'verdana' => 'Verdana'
      ),
      '#default_value' => $config->get('font'),
      '#description' => $this->t('The font to display in the button'),
    );
    $form['fblikebutton_static_block_appearance']['fblikebutton_color_scheme'] = array(
      '#type' => 'select',
      '#title' => $this->t('Color scheme'),
      '#options' => array('light' => $this->t('Light'), 'dark' => $this->t('Dark')),
      '#default_value' => $config->get('color_scheme', 'light'),
      '#description' => $this->t('The color scheme of box environtment'),
    );
    $form['fblikebutton_static_block_appearance']['fblikebutton_iframe_width'] = array(
      '#type' => 'number',
      '#title' => $this->t('Width of the iframe (px)'),
      '#default_value' => $config->get('iframe_width'),
      '#description' => $this->t('Width of the iframe, in pixels. Default is 450. <em>Note: lower values may crop the output.</em>'),
    );
    $form['fblikebutton_static_block_appearance']['fblikebutton_iframe_height'] = array(
      '#type' => 'number',
      '#title' => $this->t('Height of the iframe (px)'),
      '#default_value' => $config->get('iframe_height', 80),
      '#description' => $this->t('Height of the iframe, in pixels. Default is 80. <em>Note: lower values may crop the output.</em>'),
    );
    $form['fblikebutton_static_block_appearance']['fblikebutton_iframe_css'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Extra css styling needed'),
      '#default_value' => $config->get('iframe_css'),
      '#description' => $this->t('Extra css attributes needed to make the iframe behave for your specific requirements. Will not necessarily overwrite existing styling. To alter the dimensions of the iframe, use the height and width fields found above.<br/>Example: <em>float: right; padding: 5px;</em>'),
    );
    $form['fblikebutton_static_block_appearance']['fblikebutton_block']['fblikebutton_language'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Language'),
      '#default_value' => $config->get('language', 'en_US'),
      '#description' => $this->t('Specific language to use. Default is English. Examples:<br />French (France): <em>fr_FR</em><br />French (Canada): <em>fr_CA</em><br />More information can be found at http://developers.facebook.com/docs/internationalization/ and a full XML list can be found at http://www.facebook.com/translations/FacebookLocales.xml'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
  * {@inheritdoc}
  */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (null !== $form_state->getValue('fblikebutton_iframe_width')) {
      if ((!is_numeric($form_state->getValue('fblikebutton_iframe_width'))) || ($form_state->getValue('fblikebutton_iframe_width') <= 0)) {
        $form_state->setErrorByName('fblikebutton_iframe_width', $this->t('The width of the like button must be a positive number that is greater than 0 (examples: 201 or 450 or 1024).'));
      }
    }
    if (null !== $form_state->getValue('fblikebutton_iframe_height')) {
      if ((!is_numeric($form_state->getValue('fblikebutton_iframe_height'))) || ($form_state->getValue('fblikebutton_iframe_height') <= 0)) {
        $form_state->setErrorByName('fblikebutton_iframe_height', $this->t('The height of the like button must be a positive number that is greater than 0 (examples: 201 or 450 or 1024).'));
      }
    }
    if (null !== $form_state->getValue('fblikebutton_bl_iframe_width')) {
      if ((!is_numeric($form_state->getValue('fblikebutton_bl_iframe_width'))) || ($form_state->getValue('fblikebutton_bl_iframe_width') <= 0)) {
        $form_state->setErrorByName('fblikebutton_bl_iframe_width', $this->t('The width of the like button must be a positive number that is greater than 0 (examples: 201 or 450 or 1024).'));
      }
    }
    if (null != $form_state->getValue('fblikebutton_bl_iframe_height')) {
      if ((!is_numeric($form_state->getValue('fblikebutton_bl_iframe_height'))) || ($form_state->getValue('fblikebutton_bl_iframe_height') <= 0)) {
        $form_state->setErrorByName('fblikebutton_bl_iframe_height', $this->t('The height of the like button must be a positive number that is greater than 0 (examples: 201 or 450 or 1024).'));
      }
    }
    if (null != $form_state->getValue('fblikebutton_weight')) {
      if (!is_numeric($form_state->getValue('fblikebutton_weight'))) {
        $form_state->setErrorByName('fblikebutton_weight', $this->t('The weight of the like button must be a number (examples: 50 or -42 or 0).'));
      }
    }
    if (null != $form_state->getValue('fblikebutton_bl_weight')) {
      if (!is_numeric($form_state->getValue('fblikebutton_bl_weight'))) {
        $form_state->setErrorByName('fblikebutton_bl_weight', $this->t('The weight of the like button must be a number (examples: 50 or -42 or 0).'));
      }
    }
  }

  /**
  * {@inheritdoc}
  */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $config = $this->config('fblikebutton.static_settings');

    $block_url = $form_state->getValue('fblikebutton_block_url');
    $layout = $form_state->getValue('fblikebutton_layout');
    $show_faces = $form_state->getValue('fblikebutton_show_faces');
    $action = $form_state->getValue('fblikebutton_action');
    $font = $form_state->getValue('fblikebutton_font');
    $color_scheme = $form_state->getValue('fblikebutton_color_scheme');
    $iframe_width = $form_state->getValue('fblikebutton_iframe_width');
    $iframe_height = $form_state->getValue('fblikebutton_iframe_height');
    $iframe_css = $form_state->getValue('fblikebutton_iframe_css');
    $language = $form_state->getValue('fblikebutton_language');

    $config->set('block_url', $block_url)
          ->set('layout', $layout)
          ->set('show_faces', $show_faces)
          ->set('action', $action)
          ->set('font', $font)
          ->set('color_scheme', $color_scheme)
          ->set('iframe_width', $iframe_width)
          ->set('iframe_height', $iframe_height)
          ->set('iframe_css', $iframe_css)
          ->set('language', $language)
          ->save();
  }
}
