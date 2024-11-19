<?php

namespace App\Livewire\Pages\Documents;

use App\Models\Document;
use App\Models\Type;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

use function Pest\Laravel\get;

class ListDocumentUser extends Component
{

    public $dataDocumet;
    public $dataDocuments;
   
    public function mount()  {
        
        $this->dataDocumet = Auth::user();
        $this->dataDocuments = Document::with("type")
                            ->where("user_id", "=", Auth::user()->id)
                            ->get();                       
    }
  
    public function download($url, $name)
    {
        return response()->download($url, $name);
    }

    public function render()
    {
        
        return view('livewire.pages.documents.list-document-user');
    }
}
