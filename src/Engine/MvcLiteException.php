<?php

namespace MvcliteCore\Engine;

use Exception;
use MvcliteCore\Engine\InternalResources\Storage;

class MvcLiteException extends Exception
{
    /** MVCLite exception dialog title. */
    private string $title;

    /**
     * If this exception must be rendered
     * even if it is forbidden by config.php file.
     */
    private bool $overrideExceptionRenderingAuthorization;

    public function __construct()
    {
        parent::__construct();

        $this->code = "MVCLITE_UNKNOWN_ERROR";
        $this->message = "An unknwon error is thrown by MVCLite.";

        $this->title = "MVCLite Fatal Error";
        $this->overrideExceptionRenderingAuthorization = false;
    }

    /**
     * @return string HTML error dialog window
     */
    public function getDialog(): string
    {
        $code = $this->getCode();
        $message = $this->getMessage();
        $file = $this->getFile();
        $title = $this->getTitle();

        self::importCss();

        $html = "<div mvclite-dialog>
                     <div class='dialog-window'>
                         <h1>
                             $title
                         </h1>
    
                         <hr />
                         
                         <p>
                             <span class='bold'>Code:</span>
                             <span class='mono'>$code</span>
                         </p>
                         
                         <p>
                             <span class='bold'>File:</span>
                             <span class='mono'>$file</span>
                         </p>
                         
                         " . ($message ? "<p class='mono message'>$message</p>" : "") . "
                     </div>
                 </div>";

        return $html;
    }

    /**
     * HTML error dialog window rendering.
     */
    public function render(): void
    {
        if ($this->canRenderIt())
        {
            echo $this->getDialog();
        }

        die;
    }

    /**
     * Import debug rendering CSS.
     */
    private static function importCss(): void
    {
//        $debugCss = file_get_contents(dirname(__DIR__
//                      . "/InternalResources/ExceptionRendering/rendering.css"));

        $debugCss = 'div[mvclite-dialog] {
                       position: fixed;
                       inset: 0;
                       background: rgba(0, 0, 0, 0.7);
                       display: flex;
                       justify-content: center;
                       align-items: center;
                       font-family: sans-serif;
                     }
                     div[mvclite-dialog] > .dialog-window {
                       border: none;
                       border-radius: 10px;
                       padding: 10px 50px;
                       background: white;
                       width: 75%;
                       box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
                     }
                     div[mvclite-dialog] > .dialog-window > h1 {
                       font-weight: 900;
                     }
                     div[mvclite-dialog] > .dialog-window hr {
                       border: solid 1px #E6E6E6;
                     }
                     div[mvclite-dialog] > .dialog-window p.message {
                       font-size: 15px;
                       padding: 5px;
                       border: 1px solid grey;
                       border-radius: 5px;
                       background: #E6E6E6;
                     }
                     div[mvclite-dialog] > .dialog-window p > span.mono {
                       font-size: 20px;
                     }
                     div[mvclite-dialog] > .dialog-window .bold {
                       font-weight: 900;
                     }
                     div[mvclite-dialog] > .dialog-window .mono {
                       font-family: monospace;
                     }
                     ';

        echo "<style>$debugCss</style>";
    }

    /**
     * @return string MVCLite exception title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title New MVCLite exception dialog title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return bool If exception can be rendered
     */
    public function canRenderIt(): bool
    {
        return $this->overrideExceptionRenderingAuthorization
               || defined("PREFERENCES")
                  && PREFERENCES["render_mvclite_exceptions"];
    }

    /**
     * Override MVCLite exceptions rendering
     * preferences parameter.
     */
    protected function forceRendering(): void
    {
        $this->overrideExceptionRenderingAuthorization = true;
    }
}