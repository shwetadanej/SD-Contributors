<?php
use SD\ContributorsAdmin\Sd_Contributors_Admin;
use PHPUnit\Framework\TestCase;

class SDContributorsTest extends TestCase {
    public function setUp(): void {
        $_POST = [];
        parent::setUp();
        WP_Mock::setUp();
    }
    public function tearDown(): void {

        WP_Mock::tearDown();
        Mockery::close();
        parent::tearDown();
    }

    public function testSaveMetaBox() {
        // Mock the post ID
        $postId = 1;
        
        $_POST['save_contributor_action_nonce_field'] = 'mocked_nonce'; // Manually set the nonce value

        $_POST['sd_authors'] = array(1, 2);
        $sd_authors = $_POST['sd_authors'];

        // Set up expectations for get_post_type
        \WP_Mock::userFunction('get_post_type', array(
            'args' => $postId,
            'times' => 1,
            'return' => 'post',
        ));

        // Set up expectations for wp_verify_nonce
        \WP_Mock::userFunction('wp_verify_nonce', array(
            'args' => array($_POST['save_contributor_action_nonce_field'], 'save_contributor_action_nonce'),
            'times' => 1,
            'return' => true,
            'passthru' => true,
        ));

        // Mock sanitize_text_field
        \WP_Mock::userFunction('sanitize_text_field', array(
            'times' => count($sd_authors),
            'return_in_order' => array(1, 2),
        ));

        \WP_Mock::userFunction('wp_unslash', array(
            'args' => array($sd_authors),
            'return' => $sd_authors,
        ));

        // Set up expectations for get_post_meta
        \WP_Mock::userFunction('get_post_meta', array(
            'args' => array($postId, 'sd_contributors', true),
            'times' => 1,
            'return' => array(3),
        ));

        // Set up expectations for update_post_meta
        \WP_Mock::userFunction('update_post_meta', array(
            'args' => array($postId, 'sd_contributors', $sd_authors),
            'times' => 1,
        ));

        \WP_Mock::userFunction('get_user_meta', array(
            'times' => 2,
            'return_in_order' => array(
                array(1, 'sd_contributor_post', true), 
                array(2, 'sd_contributor_post', true)
            ),
        ));
        

        \WP_Mock::userFunction('update_user_meta', array(
            'times' => 2,
            'return_in_order' => array(
                array(1, 'sd_contributor_post', array($postId)), 
                array(2, 'sd_contributor_post', array($postId))
            ),
        ));
        
        // Instantiate your class
        $contributorsObject = new Sd_Contributors_Admin("sd-contributors", "1.0.0");

        // Call the function
        $contributorsObject->save_meta_box($postId);
    }
}