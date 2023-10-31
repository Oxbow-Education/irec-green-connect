<?php
function get_radio_buttons($questionNumber, $questionScores)
{
  extract($questionScores);
  return "<div class='image-radio-group'>
    <label>
      <input type='radio' name='question_$questionNumber' value='$strongly_disagree' class='image-radio'>
      <img src='/wp-content/plugins/irec-green-connect/public/img/strongly-disagree.svg' alt='Strongly Disagree'>
      Strongly Disagree
    </label>
    <label>
      <input type='radio' name='question_$questionNumber' value='$disagree' class='image-radio'>
      <img src='/wp-content/plugins/irec-green-connect/public/img/disagree.svg' alt='Disagree'>
      Disagree
    </label>
    <label>
      <input type='radio' name='question_$questionNumber' value='0' class='image-radio'>
      <img src='/wp-content/plugins/irec-green-connect/public/img/unsure.svg' alt='Unsure'>
      Unsure
    </label>
    <label>
      <input type='radio' name='question_$questionNumber' value='$agree' class='image-radio'>
      <img src='/wp-content/plugins/irec-green-connect/public/img/agree.svg' alt='Agree'>
      Agree
    </label>
    <label>
    <input type='radio' name='question_$questionNumber' value='$strongly_agree' class='image-radio'>
    <img src='/wp-content/plugins/irec-green-connect/public/img/strongly-agree.svg' alt='Strongly Agree'>
    Strongly Agree
</label>
  </div>";
}

$strong_weighting = array(
  'strongly_agree' => '6',
  'agree' => '3',
  'unsure' => '0',
  'disagree' => '-3',
  'strongly_disagree' => '-6'
);
$medium_weighting = array(
  'strongly_agree' => '4',
  'agree' => '2',
  'unsure' => '0',
  'disagree' => '-2',
  'strongly_disagree' => '-4'
);
$light_weighting = array(
  'strongly_agree' => '2',
  'agree' => '1',
  'unsure' => '0',
  'disagree' => '-1',
  'strongly_disagree' => '-2'
);
?>

<button id="quizButton" class="quiz-cta">Take the Quiz
  <img src="/wp-content/plugins/irec-green-connect/public/img/arrow-circle.svg" alt="" />

</button>

<div id="overlay" class="hidden">
  <div id="modal" class="hidden">
    <img class="results-image" />

    <form id="quizForm" action="" method="post">
      <div class="quiz-slide" data-slide="1">
        <h2>I would like to <strong>
            work with my hands, tools, and equipment
          </strong> in my day to day work.</h2>
        <?php echo get_radio_buttons('1', $strong_weighting); ?>
      </div>
      <div class="quiz-slide" data-slide="2">
        <h2>I would like to learn about how <strong>
            energy efficiency
          </strong> can make homes healthier and teach others about that too.</h2>
        <?php echo get_radio_buttons('2', $medium_weighting); ?>
        <button type="button" class="prev-btn">Back</button>

      </div>
      <div class="quiz-slide" data-slide="3">
        <h2>I would like to <strong>help families in need</strong> save money on their energy bills and improve their quality of life.</h2>
        <!-- Add question content here -->
        <?php echo get_radio_buttons('3', $medium_weighting); ?>
        <button type="button" class="prev-btn">Back</button>
      </div>
      <div class="quiz-slide" data-slide="4">
        <h2>I am seeking <strong>stable work with a great opportunity</strong> for promotion or raises in the future.</h2>
        <!-- Add question content here -->
        <?php echo get_radio_buttons('4', $light_weighting); ?>
        <button type="button" class="prev-btn">Back</button>
      </div>
      <div class="quiz-slide" data-slide="5">
        <h2>I am <strong>a team player</strong> and enjoy working with staff members. I'd rather do that than work alone.</h2>
        <!-- Add question content here -->
        <?php echo get_radio_buttons('5', $medium_weighting); ?>
        <button type="button" class="prev-btn">Back</button>
      </div>
      <div class="quiz-slide" data-slide="6">
        <h2>I’m willing and able to work in <strong>attics and crawl spaces</strong> where this work often takes place.</h2>
        <!-- Add question content here -->
        <?php echo get_radio_buttons('6', $strong_weighting); ?>
        <button type="button" class="prev-btn">Back</button>
      </div>
      <div class="quiz-slide" data-slide="7">
        <h2>Last question! <strong>How many of the following interest you?</strong> </h2>
        <div class="checkbox-wrapper">

          <label class="quiz-checkbox">
            <input name="question_7[]" value="1" type="checkbox">
            <span class="checkmark"></span>
            Find on-the-job training: I can be paid to learn new skills.
          </label>
          <label class="quiz-checkbox">
            <input name="question_7[]" value="1" type="checkbox">
            <span class="checkmark"></span>
            Earn nationwide certifications: I can get credentials to use anywhere in the U.S.
          </label>
          <label class="quiz-checkbox">
            <input name="question_7[]" value="1" type="checkbox">
            <span class="checkmark"></span>
            Join the home energy industry: I can help communities be more climate resilient.
          </label>
        </div>

        <!-- Add question content here -->
        <button id="quizSubmitButton" type="submit" class="quiz-cta">Get My Results</button>
        <button type="button" class="prev-btn">Back</button>
      </div>
      <div class="quiz-slide results" data-slide="MATCH">
        <h2>Excellent Match</h2>
        <h6>Run, don't walk to opportunities to join this exciting field!</h6>
        <p>You can probably handle the work's challenges and may value its many rewards. You could be helping your neighbors and community save money on their home energy bills while advancing your own career in a matter of weeks or months. We hope you'll join us.</p>
        <hr />

        <div class="actions">
          <div>To get started as soon as possible&nbsp;

            <div class="inline-block">

              <?php
              echo do_shortcode('[elementor-template id="5687"]');
              ?>
            </div>

            &nbsp;
            with an organization. If you'd like to
            keep exploring, check out
            <a href="/how-it-works-for-individuals">How it Works for Individuals</a>
            or
            <a href="/career-stories">Career Stories.</a>
          </div>

        </div>
      </div>

      <div class="quiz-slide results" data-slide="MAYBE">
        <h2>Maybe a Match</h2>
        <h6>You may be a good fit for this type of career and its many rewards, or you may find some parts of it not to your liking.</h6>
        <hr />

        <div class="actions">
          <div>Go ahead and check out
            <a href="/how-it-works-for-individuals/">How it Works for Individuals</a>
            or
            <a href="/career-stories">Career Stories.</a> to learn more about the reality of the work. If you'd like to talk to someone in your area about local opportunities,
            &nbsp;
            <div class="inline-block">

              <?php
              echo do_shortcode('[elementor-template id="5687"]');
              ?>
            </div>&nbsp; with an organization.
          </div>

        </div>
      </div>
      <div class="quiz-slide results" data-slide="NOT">
        <h2>Probably Not a Match</h2>
        <h6>The particular career paths we're spotlighting in Weatherization might not be for you, but there are so many other options in the Clean Energy industry available to you that might suit you.</h6>
        <hr />

        <div class="actions">
          <p>Here's a way to explore other career paths in Clean Energy:
            <a href="https://www.irecusa.org/career-maps/" target="_blank">Browse Career Maps in Clean Energy</a>
            or talk to a <a href="https://www.careeronestop.org/LocalHelp/AmericanJobCenters/find-american-job-centers.aspx" target="_blank">Local Job Center</a>
            for one on one support. If you're still curious about Weatherization careers, keep browsing!
          </p>

        </div>
      </div>
      <button id="closeButton"><svg xmlns="http://www.w3.org/2000/svg" width="22.318" height="22.379" viewBox="0 0 22.318 22.379">
          <path d="M21.77,114.654a1.674,1.674,0,0,1-2.367,2.367l-8.242-8.3-8.3,8.3a1.674,1.674,0,0,1-2.367-2.367l8.3-8.3L.49,97.99a1.674,1.674,0,0,1,2.367-2.367l8.3,8.366,8.3-8.3a1.674,1.674,0,0,1,2.367,2.367l-8.3,8.3Z" transform="translate(0 -95.133)" />
        </svg></button>
    </form>

  </div>
</div>