<?php

/**
* Contains \Drupal\fblikebutton\Form\DynamicFormSettings
*/

namespace Drupal\fblikebutton\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class DynamicFormSettings extends ConfigFormBase {

  /**
  * {@inheritdoc}
  */
  public function getFormId() {
    return 'fblikebutton_dynamic';
  }

  /**
  * {@inheritdoc}
  */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $fblikebutton_node_options = node_type_get_names();
    $config = $this->config('fblikebutton.settings');

    $form['fblikebutton_dynamic_description'] = array(
      '#markup' => '<p>' . $this->t('Configure the dynamic Like button. This Like button will like the URL you\'re visiting. You can set the content types on which the button displays, choose to display it in the content block or it\'s own block and set the appearance.') . '</p>',
    );
    $form['fblikebutton_dynamic_visibility'] = array(
      '#type' => 'details',
      '#title' => $this->t('Visibility settings'),
      '#open' => TRUE,
    );
    $form['fblikebutton_dynamic_visibility']['fblikebutton_node_types'] = array(
      '#type' => 'checkboxes',
      '#title' => $this->t('Display the Like button on these content types:'),
      '#options' => $fblikebutton_node_options,
      '#default_value' => $config->get('node_types'),
      '#description' => $this->t('Each of these content types will have the "like" button automatically added to them.'),
    );
    $form['fblikebutton_dynamic_visibility']['fblikebutton_full_node_display'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Where do you want to show the Like button (full node view)?'),
      '#options' => array(
        $this->t('Content area'),
        $this->t('Own block'),
        $this->t('Links area')
      ),
      '#default_value' => $config->get('full_node_display'),
      '#description' => $this->t('If <em>Content area</em> is selected, the button will appear in the same area as the node content. If <em>Own block</em> is selected the Like button gets it\'s own block, which you can position at the ' . \Drupal::l($this->t('block page'), Url::fromRoute('block.admin_display')) . '. When you select <em>Links area</em> the Like button will be visible in the links area, usually at the bottom of the node (When you select this last option you may want to adjust the Appearance settings).'),
    );
    $form['fblikebutton_dynamic_visibility']['fblikebutton_teaser_display'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Where do you want to show the Like button on teasers?'),
      '#options' => array(
        $this->t('Don\'t show on teasers'),
        $this->t('Content area'),
        $this->t('Links area')
      ),
      '#default_value' => $config->get('teaser_display'),
      '#description' => $this->t('If you want to show the like button on teasers you can select the display area.'),
    );
    $form['fblikebutton_dynamic_appearance'] = array(
      '#type' => 'details',
      '#title' => $this->t('Appearance settings'),
      '#open' => TRUE,
    );
    $form['fblikebutton_dynamic_appearance']['fblikebutton_iframe_width'] = array(
      '#type' => 'number',
      '#title' => $this->t('Width of the iframe (px)'),
      '#default_value' => $config->get('iframe_width'),
      '#description' => $this->t('Width of the iframe, in pixels. Default is 450. <em>Note: lower values may crop the output.</em>'),
    );
    $form['fblikebutton_dynamic_appearance']['fblikebutton_iframe_height'] = array(
      '#type' => 'number',
      '#title' => $this->t('Height of the iframe (px)'),
      '#default_value' => $config->get('iframe_height'),
      '#description' => $this->t('Height of the iframe, in pixels. Default is 80. <em>Note: lower values may crop the output.</em>'),
    );
    $form['fblikebutton_dynamic_appearance']['fblikebutton_iframe_css'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Extra css styling needed'),
      '#default_value' => $config->get('iframe_css'),
      '#description' => $this->t('Extra css attributes needed to make the iframe behave for your specific requirements. Will not necessarily overwrite existing styling. To alter the dimensions of the iframe, use the height and width fields found above.<br/>Example: <em>float: right; padding: 5px;</em>'),
    );
    $form['fblikebutton_dynamic_appearance']['fblikebutton_layout'] = array(
      '#type' => 'select',
      '#title' => $this->t('Layout style'),
      '#options' => array('standard' => $this->t('Standard'),
                          'box_count' => $this->t('Box Count'),
                          'button_count' => $this->t('Button Count')),
      '#default_value' => $config->get('layout'),
      '#description' => $this->t('Determines the size and amount of social context next to the button.'),
    );
    // The actial values passed in from the options will be converted to a boolean
    // in the validation function, so it doesn't really matter what we use.
    $form['fblikebutton_dynamic_appearance']['fblikebutton_show_faces'] = array(
      '#type' => 'select',
      '#title' => $this->t('Show faces in the box?'),
      '#options' => array(TRUE => $this->t('Show faces'), FALSE => $this->t('Do not show faces')),
      '#default_value' => $config->get('show_faces', TRUE),
      '#description' => $this->t('Show profile pictures below the button. Only works if <em>Layout style</em> (found above) is set to <em>Standard</em> (otherwise, value is ignored).'),
    );
    $form['fblikebutton_dynamic_appearance']['fblikebutton_action'] = array(
      '#type' => 'select',
      '#title' => $this->t('Verb to display'),
      '#options' => array('like' => $this->t('Like'), 'recommend' => $this->t('Recommend')),
      '#default_value' => $config->get('action'),
      '#description' => $this->t('The verbiage to display inside the button itself.'),
    );
    $form['fblikebutton_dynamic_appearance']['fblikebutton_font'] = array(
      '#type' => 'select',
      '#title' => $this->t('Font'),
      '#options' => array(
        'arial' => 'Arial',
        'lucida+grande' => 'Lucida Grande',
        'segoe+ui' => 'Segoe UI',
        'tahoma' => 'Tahoma',
        'trebuchet+ms' => 'Trebuchet MS',
        'verdana' => 'Verdana',
      ),
      '#default_value' => $config->get('font', 'arial'),
      '#description' => $this->t('The font with which to display the text of the button.'),
    );
    $form['fblikebutton_dynamic_appearance']['fblikebutton_color_scheme'] = array(
      '#type' => 'select',
      '#title' => $this->t('Color scheme'),
      '#options' => array('light' => $this->t('Light'), 'dark' => $this->t('Dark')),
      '#default_value' => $config->get('color_scheme'),
      '#description' => $this->t('The color scheme of the box environtment.'),
    );
    $form['fblikebutton_dynamic_appearance']['fblikebutton_weight'] = array(
      '#type' => 'number',
      '#title' => $this->t('Weight'),
      '#default_value' => $config->get('weight'),
      '#description' => $this->t('The weight determines where, at the content block, the like button will appear. The larger the weight, the lower it will appear on the node. For example, if you want the button to appear more toward the top of the node, choose <em>-40</em> as opposed to <em>-39, -38, 0, 1,</em> or <em>50,</em> etc. To position the Like button in its own block, go to the ' . \Drupal::l($this->t('block page'), Url::fromRoute('block.admin_display')) . '.'),
      );
    $form['fblikebutton_dynamic_appearance']['fblikebutton_language'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Language'),
      '#default_value' => $config->get('language'),
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
    if (null != $form_state->getValue('fblikebutton_weight')) {
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
    $config = $this->config('fblikebutton.settings');

    $node_types = $form_state->getValue('fblikebutton_node_types');
    $full_node_display = $form_state->getValue('fblikebutton_full_node_display');
    $teaser_display = $form_state->getValue('fblikebutton_teaser_display');
    $iframe_width = $form_state->getValue('fblikebutton_iframe_width');
    $iframe_height = $form_state->getValue('fblikebutton_iframe_height');
    $iframe_css = $form_state->getValue('fblikebutton_iframe_css');
    $layout = $form_state->getValue('fblikebutton_layout');
    $show_faces = $form_state->getValue('fblikebutton_show_faces');
    $action = $form_state->getValue('fblikebutton_action');
    $font = $form_state->getValue('fblikebutton_font');
    $color_scheme = $form_state->getValue('fblikebutton_color_scheme');
    $weight = $form_state->getValue('fblikebutton_weight');
    $language = $form_state->getValue('fblikebutton_language');

    $config->set('node_types', $node_types)
          ->set('full_node_display', $full_node_display)
          ->set('teaser_display', $teaser_display)
          ->set('iframe_width', $iframe_width)
          ->set('iframe_height', $iframe_height)
          ->set('iframe_css', $iframe_css)
          ->set('layout', $layout)
          ->set('show_faces', $show_faces)
          ->set('action', $action)
          ->set('font', $font)
          ->set('color_scheme', $color_scheme)
          ->set('weight', $weight)
          ->set('language', $language)
          ->save();

    // Clear render cache
    $this->clearCache();
  }

  /**
  * Clear render cache to make the button appear or disappear
  */
  protected function clearCache() {
    \Drupal::cache('render')->deleteAll();
  }
}