<?php
require_once "../XYZ.php";
$question_num=$_POST['question_num'];
$str="";
$questionNums=array(
    1 => 'یک',
    2 => 'دو',
    3 => 'سه',
    4 => 'چهار',
    5 => 'پنج',
    6 => 'شش',
    7 => 'هفت',
    8 => 'هشت',
    9 => 'نه',
    10 => 'ده',
    11 => 'یازده',
    12 => 'دوازده',
    13 => 'سیزده',
    14 => 'چهارده',
    15 => 'پانزده',
    16 => 'شانزده',
    17 => 'هفده',
    18 => 'هجده',
    19 => 'نوزده',
    20 => 'بیست',
    21 => 'بیست و یک',
    22 => 'بیست و دو',
    23 => 'بیست و سه',
    24 => 'بیست و چهار',
    25 => 'بیست و پنج',
    26 => 'بیست و شش',
    27 => 'بیست و هفت',
    28 => 'بیست و هشت',
    29 => 'بیست و نه',
    30=>'سی');
if ($question_num > 20 or $question_num<0) $question_num=5;
for ($i=1;$i<=$question_num;$i++) {
    $question = $_SESSION['examValues']['questions'][$i - 1] ?? '';
    $rightAnswer = $_SESSION['examValues']['right_answers'][$i - 1] ?? '';
    $items = isset($_SESSION['examValues']) ? array_chunk($_SESSION['examValues']['items'], 4) : '';
    $questionItems = $items[$i-1] ??  '';
    $questionItems_1= $questionItems ? $questionItems[0]:'';
    $questionItems_2=$questionItems ? $questionItems[1]:'';
    $questionItems_3=$questionItems ? $questionItems[2]:'';
    $questionItems_4=$questionItems ? $questionItems[3]:'';
    $str .= '<div class="mb-2">
                            <label for="question-name-' . $i . '" class="text-info">   سوال ' . $questionNums[$i] . '<span class="require">*</span></label>
                            <textarea name="questions[]" id="question-name-' . $i . '" form="exam-setting" class="uk-textarea" placeholder="صورت سوال را وارد کنید">' . $question . '</textarea>
              </div>
                        <span class="text-dark label"> گزینه ها <span class="require">*</span></span>
                        <div class="row justify-content-between">
                            <div class="items col-7 col-lg-4 ">
                                <div class="mb-2  d-flex">
                                    <label for="item-1" class="text-dark uk-display-inline uk-label-warning uk-label align-self-center">1</label>
                                    <input name="items[]" id="item-1" form="exam-setting" class="uk-input mx-2"  type="text" placeholder="گزینه اول" value="'.$questionItems_1.'">
                                    <span class="require">*</span>
                                </div>
                                <div class="mb-2  d-flex">
                                    <label for="item-2" class="text-dark uk-display-inline uk-label-warning uk-label align-self-center">2</label>
                                    <input name="items[]" id="item-2" form="exam-setting" class="uk-input mx-2"  type="text" placeholder="گزینه دوم" value="'.$questionItems_2.'">
                                    <span class="require">*</span>
                                </div>
                                <div class="mb-2 d-flex">
                                    <label for="item-3" class="text-dark uk-display-inline uk-label-warning uk-label align-self-center">3</label>
                                    <input name="items[]" id="item-3" form="exam-setting" class="uk-input mx-2" type="text" placeholder="گزینه سوم" value="'.$questionItems_3.'">
                                    <span class="require">*</span>
                                </div>
                                <div class="mb-2 d-flex">
                                    <label for="item-4" class="text-dark uk-display-inline uk-label-warning uk-label align-self-center">4</label>
                                    <input name="items[]" id="item-4" form="exam-setting" class="uk-input mx-2" type="text" placeholder="گزینه چهارم" value="'.$questionItems_4.'">
                                    <span class="require">*</span>
                                </div>
                            </div>
                            <div class="col-7 col-lg-4 align-self-end mb-2">
                                <label for="right-answer-' . $i . '" class="text-dark"> گزینه صحیح<span class="require">*</span></label>
                                <input name="right_answers[]" id="right-answer-' . $i . '" form="exam-setting" class="uk-textarea" type="number" min="1" max="4" placeholder="یک گزینه را وارد کنید" value="' . $rightAnswer . '">
                            </div>
                        </div>
                        <hr> ';
}
echo $str;