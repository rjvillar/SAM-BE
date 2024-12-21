<?php
class Island
{
    public $id;
    public $name;
    public $color;
    public $image;
    public $longDescription;
    public $shortDescription;

    public function __construct($id, $name, $color, $image, $longDescription, $shortDescription)
    {
        $this->id = $id;
        $this->name = $name;
        $this->color = $color;
        $this->image = $image;
        $this->longDescription = $longDescription;
        $this->shortDescription = $shortDescription;
    }

    public function generateIslandSection($contentResults)
    {
        $contentHtml = '';
        while ($content = $contentResults->fetch_assoc()) {
            $contentHtml .= '
                <div class="content">
                    <img class="rounded content-img mt-3 mb-0" src="' . $content['image'] . '" alt="' . $content['content'] . '">
                    <p class="fs-5 text-center">' . $content['content'] . '</p>
                </div>
            ';
        }

        return '
            <div class="container-fluid py-5" id="' . strtolower(str_replace(' ', '_', $this->name)) . '" style="background-color: ' . $this->color . ';">
                <div class="container">
                    <h1 class="text-center text-muted"><b>' . $this->name . '</b></h1>
                    <p class="text-center fs-5 mb-4 text-muted">' . $this->longDescription . '</p>
                    <img class="rounded img-fluid island-img my-1" src="' . $this->image . '" style="width:100%;height:auto;">
                    <p class="text-muted"><i>' . $this->shortDescription . '</i></p><br>

                    <p class="text-center">
                        <span class="fs-5">More Contents</span>
                    </p>

                    ' . $contentHtml . '
                </div>
            </div>
        ';
    }
}
