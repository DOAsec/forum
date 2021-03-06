<div class="forumsection">
	<?php
	$userdata = db_queryById("accounts", $usercache, $_GET["user"]);

	if ($userdata != false) {

		// Query rank if not cached
		$rankcache[$userdata["rankid"]] = $rank = db_queryById("ranks", $rankcache, $userdata["rankid"]);

		// Query group if not cached
		$groupcache[$userdata["groupid"]] = $group = db_queryById("groups", $groupcache, $userdata["groupid"]);

		?>
		<div class="usergrid">
			<div class="usercolumn profile">
				<h4><b>About</b></h4>
				<div class="infobox">
					<div style="text-align: center"><?php echo display_avatar($userdata); ?></div>
					<table>
						<tr>
							<td>
								<b>Username:</b>
							</td>
							<td>
								<?php echo '<span style="color: '.htmlspecialchars($rank["color"]).';">'.htmlspecialchars($userdata["username"]).'</span>'; ?>
							</td>
						</tr>
						<tr>
							<td>
								<b>Group:</b>
							</td>
							<td>
								<?php echo '<span style="color: '.htmlspecialchars($group["color"]).';">'.htmlspecialchars($group["name"]).'</span>'; ?>
							</td>
						</tr>
						<tr>
							<td>
								<b>Rank:</b>
							</td>
							<td>
								<?php echo '<span style="color: '.htmlspecialchars($rank["color"]).';">'.htmlspecialchars($rank["name"]).'</span>'; ?>
							</td>
						</tr>
						<tr>
							<td>
								<b>Joined:</b>
							</td>
							<td>
								<?php echo htmlspecialchars($userdata["regtime"]); ?>
							</td>
						</tr>
						<tr>
							<td>
								<b>Posts:</b>
							</td>
							<td>
								<?php echo htmlspecialchars($userdata["postcount"]); ?>
							</td>
						</tr>	
						<?php 
						if (isset($_SESSION["token"]["userdata"])) {
							$user = $_SESSION["token"]["userdata"];


							if ($_SESSION["token"]["userrank"]["isadmin"] > 0 || $_SESSION["token"]["userrank"]["ismoderator"] > 0) {
								?>
								<tr>
									<td>
										<b>Registration IP:</b>
									</td>
									<td>
										<?php echo htmlspecialchars($userdata["regip"]); ?>
									</td>
								</tr>
								<tr>
									<td>
										<b>Email Address:</b>
									</td>
									<td>
										<?php
										if ($_SESSION["token"]["userrank"]["isadmin"] > 0 || $user["id"] == $userdata["id"]) {
											?>
											<form method="POST">
												<input type="text" name="email" value="<?php echo htmlspecialchars($userdata["email"]); ?>" />
												<input type="submit" name="change_email" value="Change" />
											</form>
											<?php
										} else {
											echo htmlspecialchars($userdata["email"]);
										}
										?>
									</td>
								</tr>
								<?php
							}

							if ($_SESSION["token"]["userrank"]["isadmin"] > 0 || $_SESSION["token"]["userrank"]["ismoderator"] > 0 || $user["id"] == $userdata["id"]) {
								?>
								<tr>
									<td>
										<b>Invite Codes:</b>
									</td>
									<td>
										<?php
										if ($_SESSION["token"]["userrank"]["isadmin"] > 0 || $user["id"] == $userdata["id"]) {
											?>
											<form method="POST">
												<input type="text" name="invitecount" value="<?php echo htmlspecialchars($userdata["invitecodes"]); ?>" <?php if ($_SESSION["token"]["userrank"]["isadmin"] < 1) { echo "disabled"; } ?>/>
												<input type="submit" name="change_invites" value="Change" />
												<?php
												if ($user["id"] == $userdata["id"] && $userdata["invitecodes"] > 0) {
													?>
													<input type="submit" name="change_invites_generate" value="Generate Code" />
													<?php
												}
												?>
											</form>
											<?php
										} else {
											echo htmlspecialchars($userdata["invitecodes"]);
										}
										?>
									</td>
								</tr>
								<?php
							}
						}
						?>
					</table>

					<table>
						<tr>
							<td>
								<b>Refered Users:</b>
							</td>
							<td>
								<?php
								$refered = db_queryAllByX("accounts", $userdata["id"], "refid");
								echo sizeof($refered);
								?>
							</td>
						</tr>
						<?php
						foreach ($refered as $refd) {
							$user = $refd;

							// Query rank if not cached
							$rankcache[$user["rankid"]] = $rank = db_queryById("ranks", $rankcache, $user["rankid"]);

							// Query group if not cached
							$groupcache[$user["groupid"]] = $group = db_queryById("groups", $groupcache, $user["groupid"]);
							?>
							<tr>
								<td>
									<?php echo '<a href="?user='.$user["id"].'" class="usera"><span style="color: '.htmlspecialchars($rank["color"]).';">'.htmlspecialchars($user["username"]).'</span></a>'; ?>
								</td>
								<td>
									<?php echo htmlspecialchars($user["regtime"]); ?>
								</td>
							</tr>
							<?php
						}

						?>
					</table>
				</div>
			</div>


			

			<div class="usercolumn bio">
				<h4><b>Bio</b></h4>
				<div class="infobox">
					<form method="post">
						<textarea name="bio" <?php if ($_SESSION["token"]["userrank"]["isadmin"] < 1 && $user["id"] !== $userdata["id"]) { echo "disabled"; } ?>></textarea>
						<?php
						if ($_SESSION["token"]["userrank"]["isadmin"] > 0 || $user["id"] == $userdata["id"]) {
							?>
							<input type="submit" name="change_bio" value="Change" />
							<?php
						}
						?>
					</form>
				</div>
			</div>


			

			<div class="usercolumn reputation">
				<h4><b>Reputation</b></h4>
				<div class="infobox">
					Reputation system not yet implemented.
				</div>
			</div>
			



			<div class="usercolumn threads">
				<h4>Threads</h4>
				<div class="infobox">
					
					<?php echo display_lastestThreads($userdata["id"], 15); ?>

				</div>
			</div>
			<div class="usercolumn posts">
				<h4>Posts</h4>
				<div class="infobox">
						
					<?php echo display_latestPosts($userdata["id"], 25); ?>

				</div>
			</div>
		</div>
		<?php
	} else {
		echo '<div class="noticetext">User not found.</div>';
	}
	?>
</div>