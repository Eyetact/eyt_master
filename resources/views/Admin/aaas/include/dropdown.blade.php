<div class="col-sm-12 input-box">
    <label class="form-label" for="module">Module<span class="text-red">*</span></label>
    <select name="module" class="google-input select-sub" id="module" required="">
          <option>-- Select --</option><option data-id="{{ isset($aaa) && $aaa->data_id  ? $aaa->data_id : '' }}"  {{ isset($aaa) && $aaa->sub_id == '9' ? 'selected' : '' }} data-value="ads" value="9" >ad</option>                  
    </select>
</div>
<div class="col-sm-12 input-box view-sub-form">
</div>