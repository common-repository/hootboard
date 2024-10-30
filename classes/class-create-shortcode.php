<?php
// Creates plugin's shortcode functionality.
class HB_Create_ShortCode
{
    public function __construct() {
        add_shortcode( 'hootboard', [ $this, 'create_shortcode' ] );
    }

    public function create_shortcode() {
        $option = get_option('hb_configs');

        if (!$option) return "";

        $config = json_decode($option);

        $boardId = $config->boardId;
        $boardName = str_replace(' ', '_', $config->boardName);

        $backLinks = '<div style="position:relative; bottom:25px;width: fit-content;background: #000;overflow: hidden;height: 34px;border-radius: 5px;" id="powered"><a target="_blank" href="https://hootboard.com/b/'.$boardId. '/'.$boardName.'?utm_source=hootboard_embed_code&utm_medium=web&utm_campaign=backlink_engagements&utm_content=board_view&utm_term=backlink_engagement" rel="noopener"><img loading="lazy" src="https://d24cckbkd1r6fr.cloudfront.net/465484/hoots/htmlhoots/hootfiles/images/148364I-nv5zqq.png" alt="HootBoard" style="" width="100" height="35"></a><a target="_blank" href="https://about.hootboard.com?utm_source=hootboard_embed_code&utm_medium=web&utm_campaign=backlink_engagements&utm_content=about_hootboard&utm_term=backlink_engagement" rel="noopener"><img loading="lazy" src="https://d24cckbkd1r6fr.cloudfront.net/465484/hoots/htmlhoots/hootfiles/images/148510I-l2u6zh.png" width="100" height="35"></a></div>';

        $embedCode = '<iframe style="margin:0; padding:0;" '.
            ($config->boardSize
                ? 'width="'.$config->boardSize[0].'px" height="'.$config->boardSize[1].'px"'
                : 'width="100%" height="630px"') // if responsive
        .' src="https://embed.hootboard.com/b/'.$boardId.'/'.$boardName.($config->view === "CV" ? "/calendar" : "").'?embed=true'.
            ((sizeof($config->collections) > 0 && $config->view != "CV")
                ? '&collections='.
                  join(",", $config->collections)
                : '')
        .'&view='.$config->view.'&showPosterName='.
            ($config->showPosterName ? 'true' : 'false')
        .'&showComments='.($config->showComments ? 'true' : 'false').'&collectionsUnion='.
            ($config->collectionsUnion ? 'true' : 'false').
        '" frameborder="0"></iframe>'.$backLinks;

        return $embedCode;
    }
}

new HB_Create_ShortCode();
