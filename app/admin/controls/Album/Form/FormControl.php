<?php


namespace App\Admin\Controls\Album\Form;


use App\Admin\Controls\Base\BaseControl;
use App\Repository\Album\AlbumRepository;
use App\Repository\Image\ImageRepository;
use Nette\Application\UI\Form;
use Nette\Database\DriverException;
use Nette\Forms\Controls\SubmitButton;
use Nette\Utils\DateTime;

/**
 * Class FormControl
 * @package App\Admin\Controls\Album\Form
 * @author Josef Banya
 */
class FormControl extends BaseControl
{
    /** @var string  */
    public $templateFile = 'form';

    /** @var  AlbumRepository */
    protected $albumRepository;

    /** @var  ImageRepository */
    protected $imageRepository;

    /**
     * FormControl constructor.
     * @param AlbumRepository $albumRepository
     * @param ImageRepository $imageRepository
     */
    public function __construct(AlbumRepository $albumRepository, ImageRepository $imageRepository)
    {
        parent::__construct();
        $this->setAlbumRepository($albumRepository)
            ->setImageRepository($imageRepository);
    }


    /**
     * Render method
     */
    public function render()
    {
        parent::render();
        $this->getTemplate()->render();
    }

    /**
     * @return \Nette\Application\UI\Form
     */
    public function createComponentForm()
    {
        $form = new Form();
        $form->addText('title', 'Název alba');
        $form->addMultiUpload('images', 'Obrázky');
        $form->addSubmit('submit', 'Odeslat')->onClick[] = [$this, 'processForm'];
        return $form;
    }

    /**
     * @param SubmitButton $button
     */
    public function addImageForm(SubmitButton $button)
    {
        $button->parent->createOne();
    }

    /**
     * @param SubmitButton $button
     */
    public function removeImageForm(SubmitButton $button)
    {
        $users = $button->parent->parent;
        $users->remove($button->parent, TRUE);
    }

    /**
     * @param \Nette\Forms\Controls\SubmitButton $submitButton
     */
    public function processForm(SubmitButton $submitButton)
    {
        $form = $submitButton->getForm();
        $values = $form->getValues();

        $this->getImageRepository()->getDatabase()->beginTransaction();
        $album = $this->getAlbumRepository()->insert([
            'title' => $values->title,
            'create_date' => new DateTime(),
        ], true);

        /** @var \Nette\Http\FileUpload $image */
        foreach ($values->images as $image) {
            $uploadPath = __DIR__ . '/../../../../../www/upload';
            $imageName = $image->getName();
            $path = $uploadPath . '/';
            $dbPath =  rand(1,929). '/' . $imageName;
            $image->move($path . $dbPath);
            $imageRow = $this->getImageRepository()->insert([
                'image_file' => $dbPath,
                'create_date' => new DateTime()
            ], true);
            $this->getAlbumRepository()->getDatabase()
                ->table('album_images')->insert([
                'album_id' => $album->id,
                'image_id' => $imageRow->id,
                'is_primary' => 0
            ]);

        }

        $this->getImageRepository()->getDatabase()->commit();
        $this->getPresenter()->flashMessage('Album uloženo', 'success');
        $this->getPresenter()->redirect('default');

    }


    /**
     * @return AlbumRepository
     */
    public function getAlbumRepository()
    {
        return $this->albumRepository;
    }

    /**
     * @param AlbumRepository $albumRepository
     * @return self Provides Fluent Interface
     */
    public function setAlbumRepository($albumRepository)
    {
        $this->albumRepository = $albumRepository;
        return $this;
    }

    /**
     * @return ImageRepository
     */
    public function getImageRepository()
    {
        return $this->imageRepository;
    }

    /**
     * @param ImageRepository $imageRepository
     * @return self Provides Fluent Interface
     */
    public function setImageRepository($imageRepository)
    {
        $this->imageRepository = $imageRepository;
        return $this;
    }


}