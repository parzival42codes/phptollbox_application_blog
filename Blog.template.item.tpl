<div style="flex: 1;"
     class="card-container card-container--shadow">
    <div class="card-container-header">
        <div style="display: flex;">
            <div style="flex: 1;text-align: left;">
                {$title}
            </div>
            <div style="flex: 1;text-align: right;">
                {$date}
            </div>
        </div>
    </div>
    <div class="card-container-content">
        {$content}
    </div>
    <div class="card-container-footer">
      <a hreflang="" class="btn" >{insert/language class="ApplicationBlog" path="/item/continueReading"
        language-de_DE="Weiterlesen"
          language-en_US="Continue reading"}</a>
    </div>
</div>
