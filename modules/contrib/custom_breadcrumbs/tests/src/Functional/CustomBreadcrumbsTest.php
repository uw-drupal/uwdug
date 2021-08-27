<?php

namespace Drupal\Tests\custom_breadcrumbs\Functional;

use Drupal\Core\Url;
use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\system\Functional\Menu\AssertBreadcrumbTrait;

/**
 * Tests custom breadcrumbs.
 *
 * @group custom_breadcrumbs
 */
class CustomBreadcrumbsTest extends BrowserTestBase {

  use AssertBreadcrumbTrait;

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'block',
    'node',
    'custom_breadcrumbs',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'classy';

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $web_user = $this->drupalCreateUser([
      'bypass node access',
      'administer custom_breadcrumbs',
    ]);
    $this->drupalLogin($web_user);

    $this->drupalPlaceBlock('system_breadcrumb_block');
  }

  /**
   * Tests a custom breadcrumbs for nodes.
   */
  public function testNodeBreadcrumbs() {
    // Create Basic page and Article node types.
    $this->drupalCreateContentType([
      'type' => 'page',
      'name' => 'Basic page',
    ]);
    $this->drupalCreateContentType([
      'type' => 'article',
      'name' => 'Article',
    ]);

    // Add a custom breadcrumbs to the Basic page and Article.
    $edit = [
      'label' => 'Article breadcrumbs',
      'id' => 'article_breadcrumbs',
      'status' => 1,
      'breadcrumbPaths' => "/foo\n/bar",
      'breadcrumbTitles' => "Foo\nBar",
      'entityType' => 'node',
    ];
    $this->drupalGet('admin/structure/custom_breadcrumbs/add');
    $this->submitForm($edit, 'Save');

    $edit = [
      'label' => 'Page breadcrumbs',
      'id' => 'page_breadcrumbs',
      'status' => 1,
      'breadcrumbPaths' => "/foo2\n/bar2",
      'breadcrumbTitles' => "Foo2\nBar2",
      'entityType' => 'node',
    ];
    $this->drupalGet('admin/structure/custom_breadcrumbs/add');
    $this->submitForm($edit, 'Save');

    // An additional additional options were not available the first time due to
    // the disabled ajax. Edit the custom breadcrumbs again and select the
    // entity bundle.
    $edit = [
      'entityBundle' => 'article',
    ];
    $this->drupalGet('admin/structure/custom-breadcrumbs/article_breadcrumbs');
    $this->submitForm($edit, 'Save');
    $edit = [
      'entityBundle' => 'page',
    ];
    $this->drupalGet('admin/structure/custom-breadcrumbs/page_breadcrumbs');
    $this->submitForm($edit, 'Save');

    $this->drupalCreateNode([
      'title' => $this->randomString(),
      'id' => 1,
      'type' => 'article',
    ]);
    $this->drupalCreateNode([
      'title' => $this->randomString(),
      'id' => 2,
      'type' => 'page',
    ]);

    $home_path = Url::fromRoute('<front>')->toString();
    $this->assertBreadcrumb('node/1', [
      $home_path => 'Home',
      'foo' => 'Foo',
      'bar' => 'Bar',
    ]);
    $this->assertBreadcrumb('node/2', [
      $home_path => 'Home',
      'foo2' => 'Foo2',
      'bar2' => 'Bar2',
    ]);
  }

}
