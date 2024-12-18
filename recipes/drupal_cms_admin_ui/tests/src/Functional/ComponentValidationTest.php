<?php

declare(strict_types=1);

namespace Drupal\Tests\drupal_cms_admin_theme\Functional;

use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use Drupal\Tests\BrowserTestBase;

/**
 * @group drupal_cms_admin_theme
 */
class ComponentValidationTest extends BrowserTestBase {

  use RecipeTestTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  public function test(): void {
    $dir = realpath(__DIR__ . '/../../..');

    // The recipe should apply cleanly.
    $this->applyRecipe($dir);
    // Apply it again to prove that it is idempotent.
    $this->applyRecipe($dir);

    $account = $this->drupalCreateUser(['access navigation']);
    $this->drupalLogin($account);
    $assert_session = $this->assertSession();
    // The Help module is not installed, so a link to it should not be present
    // in the navigation.
    $footer = $assert_session->elementExists('css', 'nav > h3:contains("Administrative toolbar footer")')
      ->getParent();
    $assert_session->elementNotExists('named', ['link', 'Help'], $footer);
  }

}