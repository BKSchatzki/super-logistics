<?php 

namespace SL\Common\Traits;

use SL\Activity\Models\Carrier;
use SL\Activity\Transformers\Activity_Transformer;
use \League\Fractal\Resource\Item;

trait Last_Activity {

    function last_activity ( $resource, $resource_id ) {
        
        $activity = Carrier::where('resource_type', $resource)
        	->where('resource_id', $resource_id)
        	->orderBy('created_at', 'desc')
        	->first();
        
        $item =  new Item( $activity, new Activity_Transformer );
        
        return $this->get_response($item);
    }
}
