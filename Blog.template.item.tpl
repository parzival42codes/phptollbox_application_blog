<div style="flex: 1;"
     class="card-container card-container--shadow">
    <div class="card-container-header">
        <div style="display: flex;">
            <div style="flex: 1;text-align: left;">
                {$title}
            </div>
            <div style="flex: 1;text-align: right;">
                <div>{$date}</div>
                <br />
                <div>{$category}</div>
            </div>
        </div>
    </div>
    <div class="card-container-content">
        {$content}
    </div>
    <div class="card-container-footer">
      <a href="index.php?application=ApplicationBlogView&id={$id}&title={$titleUrl}" class="btn" >{insert/language class="ApplicationBlog" path="/item/continueReading"
        language-de_DE="Weiterlesen"
          language-en_US="Continue reading"}</a>
    </div>
</div>
