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
      <input type='radio' name='question_$questionNumber' value='$unsure' class='image-radio'>
      <img src='/wp-content/plugins/irec-green-connect/public/img/unsure.svg' alt='Unsure'>
      Unsure
    </label>
    <label>
      <input type='radio' name='question_$questionNumber' value='$agree' class='image-radio'>
      <img src='/wp-content/plugins/irec-green-connect/public/img/agree.svg' alt='Agree'>
      Agree
    </label>
    <label>
    <input type='radio' name='question_<?php echo $questionNumber; ?>' value='<?php echo $strongly_agree; ?>' class='image-radio'>
    <img src='/wp-content/plugins/irec-green-connect/public/img/strongly-agree.svg' alt='Strongly Agree'>
    Strongly Agree
</label>
  </div>";
}
?>

<button id="quizButton">Take the Quiz</button>

<div id="overlay" class="hidden">
  <div id="modal" class="hidden">
    <form action="
    " method="post">
      <div class="quiz-slide" data-slide="1">
        <h2>I would like to <strong>
            work with my hands, tools, and equipment
          </strong> in my day to day work.</h2>
        <?php echo get_radio_buttons('1', array(
          'strongly_agree' => '1',
          'agree' => '2',
          'unsure' => '3',
          'disagree' => '4',
          'strongly_disagree' => '5'
        )); ?>


        <button type="button" class="next-btn">Next</button>
      </div>
      <div class="quiz-slide" data-slide="2">
        <h2>I would like to learn about how <strong>
            energy efficiency
          </strong> can make homes healthier and teach others about that too.</h2>
        <?php echo get_radio_buttons('2', array(
          'strongly_agree' => '1',
          'agree' => '2',
          'unsure' => '3',
          'disagree' => '4',
          'strongly_disagree' => '5'
        )); ?>
        <button type="button" class="prev-btn">Back</button>

      </div>
      <div class="quiz-slide" data-slide="3">
        <h2>I would like to <strong>help families in need</strong> save money on their energy bills and improve their quality of life.</h2>
        <!-- Add question content here -->
        <?php echo get_radio_buttons('3', array(
          'strongly_agree' => '1',
          'agree' => '2',
          'unsure' => '3',
          'disagree' => '4',
          'strongly_disagree' => '5'
        )); ?>
        <button type="button" class="prev-btn">Back</button>
      </div>
      <div class="quiz-slide" data-slide="4">
        <h2>I am seeking <strong>stable work with a great opportunity</strong> for promotion or raises in the future.</h2>
        <!-- Add question content here -->
        <?php echo get_radio_buttons('4', array(
          'strongly_agree' => '1',
          'agree' => '2',
          'unsure' => '3',
          'disagree' => '4',
          'strongly_disagree' => '5'
        )); ?>
        <button type="button" class="prev-btn">Back</button>
      </div>
      <div class="quiz-slide" data-slide="5">
        <h2>I am <strong>a team player</strong> and enjoy working with staff members. I’d rather do that than work alone.</h2>
        <!-- Add question content here -->
        <?php echo get_radio_buttons('5', array(
          'strongly_agree' => '1',
          'agree' => '2',
          'unsure' => '3',
          'disagree' => '4',
          'strongly_disagree' => '5'
        )); ?>
        <button type="button" class="prev-btn">Back</button>
      </div>
      <div class="quiz-slide" data-slide="6">
        <h2>I’m willing and able to work in <strong>attics and crawl spaces</strong> where this work often takes place.</h2>
        <!-- Add question content here -->
        <?php echo get_radio_buttons('6', array(
          'strongly_agree' => '1',
          'agree' => '2',
          'unsure' => '3',
          'disagree' => '4',
          'strongly_disagree' => '5'
        )); ?>
        <button type="button" class="prev-btn">Back</button>
      </div>
      <div class="quiz-slide" data-slide="7">
        <h2>Last question! <strong>How many of the following interest you?</strong> </h2>
        <!-- Add question content here -->
        <button type="button" class="prev-btn">Back</button>
        <button type="submit" class="next-btn">Get My Results</button>
      </div>
      <div class="quiz-slide" data-slide="8">

      </div>

      <button id="closeButton">Close</button>
    </form>

  </div>
</div>