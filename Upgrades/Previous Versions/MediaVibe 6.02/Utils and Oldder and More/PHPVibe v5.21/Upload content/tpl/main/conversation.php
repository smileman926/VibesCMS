<?php the_sidebar(); ?>
    <!-- Message Sidebar -->
    <div class="page-aside">
      <div class="page-aside-switch">
        <i class="icon icon-chevron-left"></i>
        <i class="icon icon-chevron-right"></i>
      </div>
      <div class="page-aside-inner">
          <div class="app-message-list">
          <div data-role="container">
            <div data-role="content">
              <ul class="list-group">
		<?php 
		$lists = $db->get_results(" select p1.*, p2.*, count(case when read_at = 0 and (by_user <> '".user_id()."') then 1 else null end) as unread  from ".DB_PREFIX."conversation p1 INNER JOIN ( SELECT * FROM ".DB_PREFIX."con_msgs order by at_time desc ) p2 on p2.conv = p1.c_id  where ((p1.user_one='".user_id()."') OR (p1.user_two='".user_id()."')) GROUP BY p2.conv order by p2.msg_id desc limit 0,500");
			if($lists) {
			foreach($lists as $list) {
				if($list->user_one == user_id()) {$partner = $list->user_two;} else { $partner = $list->user_one;}
				$partner = convBuddy($partner);
				echo '
				<li class="list-group-item '.(($list->c_id == token_id()) ? "active" :"").'">
                  <div class="media">
                    <div class="media-left">
                      <a class="avatar" href="'.site_url().'conversation/'.$list->c_id .'">
                        <img class="img-responsive" src="'.thumb_fix($partner->avatar, true, 40, 40).'" alt="..."><i></i></a>
                    </div>
                    <div class="media-body">
                      <h4 class="media-heading">
					  <a href="'.site_url().'conversation/'.$list->c_id .'">
					  '._html($partner->name).'
					  </a>
					  </h4>
                      <span class="media-time">'.time_ago($list->at_time).'</span>
                    </div>
                    <div class="media-right">
                     '.(($list->unread > 0 ) ? "<span class=\"badge badge-danger\">$list->unread</span>" :"").' 
                    </div>
                  </div>
                </li>
				
				';
			}
			
		}
		?>	  
             </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Message Sidebar -->
    <div class="page-main">
	<?php if($cid > 0) { ?>
      <!-- Chat Box -->
      <div class="app-message-chats">	  
        <div id="<?php echo $_conv->c_id; ?>" class="chats">
		<!-- First is fake & hidden -->
		 <div class="chat dummy-chat chat-left hidden">
            <div class="chat-avatar">
              <a target="_blank" class="avatar" href="<?php echo _html($us[user_id()]["profile"]); ?>" title="<?php echo _html($us[user_id()]["name"]); ?>">
                <img src="<?php echo _html($us[user_id()]["avatar"]); ?>" alt="<?php echo _html($us[user_id()]["name"]); ?>">
              </a>
            </div>
            <div class="chat-body">
              <div class="chat-content">
                
              </div>
            </div>
          </div>		 
		<?php 	
		$chats = $db->get_results(" select * from ".DB_PREFIX."con_msgs where conv ='".$_conv->c_id."' order by msg_id ASC limit 0,500");
		if($chats) {
		foreach ($chats as $chat){ ?>
		 <div class="chat <?php if($chat->by_user <> user_id()) {echo 'chat-right';} else {echo 'chat-left';} ?>">
            <div class="chat-avatar">
              <a target="_blank" class="avatar" href="<?php echo _html($us[$chat->by_user]["profile"]); ?>" title="<?php echo _html($us[$chat->by_user]["name"]); ?>">
                <img src="<?php echo _html($us[$chat->by_user]["avatar"]); ?>" alt="<?php echo _html($us[$chat->by_user]["name"]); ?>">
              </a>
			  <?php 
			  if($chat->by_user == user_id()) {
			  if($chat->read_at > 0 ) {
				  echo '<i class="icon chat-seen icon-check tipS tooltip-scale" data-toggle="tooltip" data-placement="right" title="'.time_ago($chat->read_at).'"></i>';
				  } 
			  }
			  ?>
            </div>
            <div class="chat-body">
              <div class="chat-content">
                <p class="tipS tooltip-scale" data-toggle="tooltip" data-placement="<?php if($chat->by_user <> user_id()) {echo 'left';} else {echo 'right';} ?>" title="<?php echo time_ago($chat->at_time);?>">
                <?php echo _html($chat->reply); ?>
                </p>
              </div>
            </div>
          </div>
		
		<?php }
           }		
		?>
          </div>
      </div>
      <!-- End Chat Box -->
	  <?php if(is_empty($_conv->closedby)) { ?>
	   <div class="emoji-holder hide">
		  :bowtie: :smile: :laughing: :blush: :smiley: :relaxed: :smirk: :heart_eyes: :kissing_heart: :kissing_closed_eyes: :flushed: :relieved: :satisfied: :grin: :wink: :stuck_out_tongue_winking_eye: :stuck_out_tongue_closed_eyes: :grinning: :kissing: :kissing_smiling_eyes: :stuck_out_tongue: :sleeping: :worried: :frowning: :anguished: :open_mouth: :grimacing: :confused: :hushed: :expressionless: :unamused: :sweat_smile: :sweat:  :weary: :pensive: :disappointed: :confounded: :fearful: :cold_sweat: :persevere: :cry: :sob: :joy: :astonished: :scream: :neckbeard: :tired_face: :angry: :rage: :triumph: :sleepy: :yum: :mask: :sunglasses: :dizzy_face: :imp: :smiling_imp: :neutral_face: :no_mouth: :innocent: :alien: :yellow_heart: :blue_heart: :purple_heart: :heart: :green_heart: :broken_heart: :heartbeat: :heartpulse: :two_hearts: :revolving_hearts: :cupid: :sparkling_heart: :sparkles: :star: :star2: :dizzy: :boom: :collision: :anger: :exclamation: :question: :grey_exclamation: :grey_question: :zzz: :dash: :sweat_drops: :notes: :musical_note: :fire: :hankey: :poop: :shit: :+1: :thumbsup: :-1: :thumbsdown: :ok_hand: :punch: :facepunch: :fist: :v: :wave: :hand: :raised_hand: :open_hands: :point_up: :point_down: :point_left: :point_right: :raised_hands: :pray: :point_up_2: :clap: :muscle: :metal: :runner: :running: :couple: :family: :two_men_holding_hands: :two_women_holding_hands: :dancer: :dancers: :ok_woman: :no_good: :information_desk_person: :bride_with_veil: :person_with_pouting_face: :person_frowning: :bow: :couplekiss: :couple_with_heart: :massage: :haircut: :nail_care: :boy: :girl: :woman: :man: :baby: :older_woman: :older_man: :person_with_blond_hair: :man_with_gua_pi_mao: :man_with_turban: :construction_worker: :cop: :angel: :princess: :smiley_cat: :smile_cat: :heart_eyes_cat: :kissing_cat: :smirk_cat: :scream_cat: :crying_cat_face: :joy_cat: :pouting_cat: :japanese_ogre: :japanese_goblin: :see_no_evil: :hear_no_evil: :speak_no_evil: :guardsman: :skull: :feet: :lips: :kiss: :droplet: :ear: :eyes: :nose: :tongue: :love_letter: :bust_in_silhouette: :busts_in_silhouette: :speech_balloon: :thought_balloon: :feelsgood: :finnadie: :goberserk: :godmode: :hurtrealbad: :rage1: :rage2: :rage3: :rage4: :suspect: :trollface:
		  :sunny: :dog: :frog: :pig_nose: :panda_face: :mouse: :pig2: :rose: :chicken: :whale: :snail: :whale2:  :monkey_face: :penguin: :monkey: :monkey: :gift_heart:
		  :key: :mag_right: :watch: :mute: :telescope: :doughnut: :coffee: :tea: :beers:  :pizza:
		  </div>
	    <?php } ?>
     
      <!-- Message Input-->
      <form class="app-message-input">
        <div class="message-input">	
<?php if(not_empty($_conv->closedby) &&  (intval($_conv->closedby) > 0 )) { ?>		
		<textarea id="insertChat" class="form-control" rows="1" disabled readonly>
		<?php echo _lang("This conversation was closed by").' '.$us[$_conv->closedby]['name'] ;?>
		</textarea>
		 <div class="message-input-actions btn-group">
            <button id="showEmoji" class="btn btn-pure btn-icon btn-default" type="button">
              <i class="icon icon-smile-o" aria-hidden="true"></i>
            </button>
          </div>
		    </div>
		<?php if($_conv->closedby == user_id()) { ?>
        <a href="<?php echo canonical();?>&open=1" class="message-input-btn btn btn-danger"><?php echo _lang("Open");?></a>
		<?php } else { ?>		
        <button class="message-input-btn btn btn-primary" type="button" disabled><?php echo _lang("SEND");?></button>
        <?php } ?>
		<?php } else { ?>
          <textarea id="insertChat" class="form-control" rows="1"></textarea>
		 <div class="message-input-actions btn-group">
            <button id="showEmoji" class="btn btn-pure btn-icon btn-default" type="button">
              <i class="icon icon-smile-o" aria-hidden="true"></i>
            </button>
			<button class="btn btn-pure btn-icon btn-default" rel="popover" data-content="<a class='btn btn-sm btn-danger' href='<?php echo canonical();?>&close=1'><?php echo _lang("Yes, close it!");?></a>" data-html="true" data-toggle="popover" data-placement="left" tabindex="0" title="<?php echo _lang("Close conversation?");?>" type="button">
		      <i class="icon icon-lock"></i>
		    </button>
            </div>
		  
        </div>
        <button id="sendChat" class="message-input-btn btn btn-primary" type="button"><?php echo _lang("SEND");?></button>
     <?php } ?>
	 </form>
      <!-- End Message Input-->
   
	<?php } else { 
			//No conversation
			echo '<div class="row text-center" style="margin-top:11%;">'._lang("Start a conversation by clicking 'Message' on that channel").'</div>';
			echo '<div class="row text-center mtop20"><a class="btn btn-primary" href="'.site_url().members.'">'._lang("Browse channels").'</a></div>';
		} ?>
 </div>