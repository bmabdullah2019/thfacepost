<?php
$poll = $params['poll'];
if(ossn_isLoggedin()) {
		$voted = $params['poll']->hasVoted();
		if($poll->container_type == 'user' || $poll->container_type == 'businesspage') {
				if(!$voted) {
						echo ossn_view_form('polls/answer', array(
								'action' => ossn_site_url('action/poll/add'),
								'params' => $params,
								'data-guid' => $params['poll']->guid,
								'class' => 'ossn-polls-form-questions '. 'ossn-polls-form-questions-' . $params['poll']->guid,
						));
				} else {
						$params['voted'] = $voted;
						echo ossn_plugin_view('polls/pages/result', $params);
				}
		} elseif($poll->container_type == 'group'){
				if(!$voted && $poll->container_type == 'group' && function_exists('ossn_get_group_by_guid')) {
						$group = ossn_get_group_by_guid($poll->container_guid);
						if($group->isMember('', ossn_loggedin_user()->guid)) {
								echo ossn_view_form('polls/answer', array(
										'action' => ossn_site_url('action/poll/add'),
										'params' => $params,
										'id' => 'ossn-polls-form-questions-' . $params['poll']->guid,
										'data-guid' => $params['poll']->guid,
										'class' => 'ossn-polls-form-questions '. 'ossn-polls-form-questions-' . $params['poll']->guid,
								));
						} else {
								echo ossn_plugin_view('polls/pages/result', $params);
						}
				} else {
					echo ossn_plugin_view('polls/pages/result', $params);
				}
		}
} else {
		echo ossn_plugin_view('polls/pages/result', $params);
}