<?php

if(class_exists("MC4WP_CommentSubscriber")) { return; }

class MC4WP_CommentSubscriber
{

	private $showed_checkbox = false;
	private $options;
	private $mc4wp;

	public function __construct(MC4WP $mc4wp) 
	{
		$this->options = $mc4wp->get_options();
		$this->mc4wp = $mc4wp;
		
		add_action ('comment_post', array($this, 'add_comment_meta'), 1);
		
		// hooks for checking if we should subscribe the commenter
		add_action('comment_approved_', array($this, 'subscribe'), 10, 1);
		add_action('comment_post', array($this, 'subscribe'), 60, 1);

		// hooks for outputting the checkbox
		add_action('thesis_hook_after_comment_box', array($this,'output_checkbox'), 20);
		add_action('comment_form', array($this,'output_checkbox'), 20);

		if($this->options['checkbox_css'] == 1) {
			add_action( 'wp_enqueue_scripts', array($this, 'load_stylesheet') );
		}
		
	}

	public function getMC4WP() {
		return $this->mc4wp;
	}

	public function load_stylesheet()
	{
		wp_enqueue_style( 'mc4wp_checkbox_css', plugins_url('mailchimp-for-wp/css/checkbox.css') );
	}

	public function subscribe($cid, $comment = null)
	{
		$cid = (int) $cid;
	
		if ( !is_object($comment) )
			$comment = get_comment($cid);
		
		// check if comment has been marked as spam or not
		if ( $comment->comment_karma == 0 ) {

			// check if commenter wanted to be subscribed
			$subscribe = get_comment_meta($cid, 'mc4wp_subscribe', true);

			if($subscribe == 1) {
				$email = $comment->comment_author_email;
				$ip = $comment->comment_author_IP;
				$name = $comment->comment_author;

				$result = $this->getMC4WP()->subscribe($email, array(
					'name' => $name,
					'ip' => $ip)
				);

				if($result) {
					update_comment_meta($cid, 'mc4wp_subscribe', 'subscribed', 1);
				}
			}
		}
	}

	public function add_comment_meta($comment_id)
	{
		 add_comment_meta($comment_id, 'mc4wp_subscribe', $_POST['mc4wp-do-subscribe'], true );
	}

	public function output_checkbox()
	{
		if($this->showed_checkbox) return;

		$opts = $this->options;
		?>
		<p id="mc4wp-checkbox">
			<input value="1" id="mc4wp-checkbox-input" type="checkbox" name="mc4wp-do-subscribe" <?php if(isset($opts['checkbox_precheck']) && $opts['checkbox_precheck'] == 1) echo 'checked="checked" '; ?>/>
			<label for="mc4wp-checkbox-input">
				<?php echo $opts['checkbox_label']; ?>
			</label>
		</p>
		<?php

		$this->showed_checkbox = true;
	}

}