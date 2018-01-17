<?php
namespace app\admin\controller;
use think\Controller;
use think\Validate;
use think\Loader;
use app\admin\model\CommidityParent;
use app\admin\model\CommidityType;
use app\admin\model\Commidity;

class CommidityInfo extends Controller
{
	//获取商品父类型信息
	public function getCommidityParentInfoForLogin()
	{
		$commidityParent = new CommidityParent;
		$result = $commidityParent -> getCommidityParentInfo(7);

		if ($result) {
			return $result;
		} else {
			return $this -> error("暂无数据):");
		}
	}

	//获取商品父类型信息
	public function getCommidityParentInfo()
	{
		$login = new Login();
		$userName = $login -> getUserSessionInfo()['name'];
		$userId = $login -> getUserSessionInfo()['id'];
		$adminImage = $login -> getUserSessionInfo()['image'];

		$commidityParent = new CommidityParent;
		$result = $commidityParent -> getCommidityParentInfo(7);

		if ($result) {
			$page = $result -> render();
			$this -> assign(
				[
					'name'                => $userName,
					'adminImage'          => $adminImage,
					'commidityParentInfo' => $result,
					'page'                => $page,
				]
			);
			return $this -> fetch("commidityParent");
		} else {
			return $this -> error("暂无数据):");
		}
	}

	//根据ID删除商品父类型
	public function deleteCommidityParentById($id)
	{
		$commidityParent = new CommidityParent;
		$res = $commidityParent -> deleteCommidityParentById($id);

		if ($res) {
			$this -> success("删除商品父类型成功！");
		} else {
			$this -> error("删除商品父类型失败！");
		}
	}

	//根据Id修改商品父类型信息
	public function modifyCommidityParentById($id)
	{
		$login = new Login();
		$userId = $login -> getUserSessionInfo()['id'];

		$commidityParentName = input('post.commidityParentName');
		
    	$data = [
			'name'        => $commidityParentName,
			'update_by'   => $userId,
			'update_time' => time(),
    	];

    	$commidityParent = new CommidityParent;
		$res = $commidityParent -> updateCommidityParentById($id, $data);

		if ($res) {
			return $this -> success('修改商品父类型信息成功！');
		} else {
			return $this -> error('修改商品父类型信息失败！');
		}		
	}

	//添加商品父类型
	public function addCommidityParent()
	{
		$login = new Login();
		$userId = $login -> getUserSessionInfo()['id'];

		$commidityParentName = input('post.commidityParentName');
		if (empty($commidityParentName)) {
			return $this -> error('商品父类型不允许为空！');
		} else {
			$data = [
				'name' => $commidityParentName,
				'create_by' => $userId,
				'create_time' => time(),
			];
			$commidityParent = new CommidityParent;
			$res = $commidityParent -> addCommidityParent($data);
			if ($res) {
				return $this -> success('添加商品父类型成功！');
			} else {
				return $this -> error('添加商品父类型失败！');
			}
		}
	}

	//根据Ids批量删除商品父类型
	public function deleteCommidityParentByIds()
	{
		//获取选择的商品父类型
		$commidityParentIds = json_decode(input('post.commidityParentIds'), true);
		if (empty($commidityParentIds)) {
			return $this -> error("请选择要删除的商品父类型！！！");
		} else {
			$commidityParent = new CommidityParent;
			$result = $commidityParent -> deleteCommidityParentByIds($commidityParentIds);

			if ($result) {
				$commidityParentIds = implode(',', $commidityParentIds);
				return $this -> success('删除 ' . $commidityParentIds . ' 成功！');
			} else {
				return $this -> error("删除失败！" . $commidityParent -> getLastSql());
			}
		}
	}

	//根据搜索条件查询商品父类型
	public function searchCommidityParent()
	{
		//查询条件
		$condition = input('post.condition');
		if (empty($condition)) {
			return $this -> error("请输入要查询的条件！！");
		} else {
			$commidityParent = new CommidityParent;
			$result = $commidityParent -> searchCommidityParent($condition);
			if ($result) {
				return $result;
			} else {
				return $this -> error("暂无数据):");
			}
		}
	}

	/*****************************************商品父类型********************************************************/

	//获取商品类型信息
	public function getCommidityTypeInfo()
	{
		$login = new Login();
		$userName = $login -> getUserSessionInfo()['name'];
		$userId = $login -> getUserSessionInfo()['id'];
		$adminImage = $login -> getUserSessionInfo()['image'];

		//获取商品父类型ＩＤ和名称
		$commidityParent = new CommidityParent;
		$commidityParentIdAndName = $commidityParent -> getCommidityParentIdAndName();

		$commidityType = new CommidityType;
		$result = $commidityType -> getCommidityTypeInfo(7);

		if ($result) {
			$page = $result -> render();
			$this -> assign(
				[
					'name'                     => $userName,
					'adminImage'               => $adminImage,
					'commidityTypeInfo'        => $result,
					'commidityParentIdAndName' => $commidityParentIdAndName,
					'page'                     => $page,
				]
			);
			return $this -> fetch("commidityType");
		} else {
			return $this -> error("暂无数据):");
		}
	}

	//根据ID删除商品类型
	public function deleteCommidityTypeById($id)
	{
		$commidityType = new CommidityType;
		$res = $commidityType -> deleteCommidityTypeById($id);

		if ($res) {
			$this -> success("删除商品类型成功！");
		} else {
			$this -> error("删除商品类型失败！");
		}
	}

	//根据Id修改商品类型信息
	public function modifyCommidityTypeById($id)
	{
		$login = new Login();
		$userId = $login -> getUserSessionInfo()['id'];

		$commidityTypeName = input('post.commidityTypeName');
		$commidityParentId = input('post.commidityParentId');
		
    	$data = [
			'name'        => $commidityTypeName,
			'p_id'        => $commidityParentId,
			'update_by'   => $userId,
			'update_time' => time(),
    	];

    	$commidityType = new CommidityType;
		$res = $commidityType -> updateCommidityTypeById($id, $data);

		if ($res) {
			return $this -> success('修改商品类型信息成功！');
		} else {
			return $this -> error('修改商品类型信息失败！');
		}		
	}

	//添加商品类型
	public function addCommidityType()
	{
		$login = new Login();
		$userId = $login -> getUserSessionInfo()['id'];

		$commidityTypeName = input('post.commidityTypeName');
		$commidityParentId = input('post.commidityParentId');

		if (empty($commidityTypeName)) {
			return $this -> error('商品类型不允许为空！');
		} else {
			$data = [
				'name'        => $commidityTypeName,
				'p_id'        => $commidityParentId,
				'create_by'   => $userId,
				'create_time' => time(),
			];
			$commidityType = new CommidityType;
			$res = $commidityType -> addCommidityType($data);
			if ($res) {
				return $this -> success('添加商品类型成功！');
			} else {
				return $this -> error('添加商品类型失败！');
			}
		}
	}

	//根据Ids批量删除商品类型
	public function deleteCommidityTypeByIds()
	{
		//获取选择的商品类型
		$commidityTypeIds = json_decode(input('post.commidityTypeIds'), true);
		if (empty($commidityTypeIds)) {
			return $this -> error("请选择要删除的商品类型！！！");
		} else {
			$commidityType = new CommidityType;
			$result = $commidityType -> deleteCommidityTypeByIds($commidityTypeIds);

			if ($result) {
				$commidityTypeIds = implode(',', $commidityTypeIds);
				return $this -> success('删除 ' . $commidityTypeIds . ' 成功！');
			} else {
				return $this -> error("删除失败！" . $commidityType -> getLastSql());
			}
		}
	}

	//根据搜索条件查询商品类型
	public function searchCommidityType()
	{
		//查询条件
		$condition = input('post.condition');
		if (empty($condition)) {
			return $this -> error("请输入要查询的条件！！");
		} else {
			//获取商品父类型ＩＤ和名称
			$commidityParent = new CommidityParent;
			$commidityParentIdAndName = $commidityParent -> getCommidityParentIdAndName();

			$commidityType = new CommidityType;
			$result = $commidityType -> searchCommidityType($condition);
			$data['commidityParentIdAndName'] = $commidityParentIdAndName;
			$data['data'] = $result;

			if ($result) {
				return $data;
			} else {
				return $this -> error("暂无数据):");
			}
		}
	}

	/*****************************************商品类型********************************************************/

	//获取商品信息
	public function getCommidityInfo()
	{
		$login = new Login();
		$userName = $login -> getUserSessionInfo()['name'];
		$userId = $login -> getUserSessionInfo()['id'];
		$adminImage = $login -> getUserSessionInfo()['image'];

		//获取商品类型ＩＤ和名称
		$commidityType = new CommidityType;
		$commidityTypeIdAndName = $commidityType -> getCommidityTypeIdAndName();

		$commidity = new Commidity;
		$result = $commidity -> getCommidityInfo(7);
		$isNew = [
			'0' => '否',
			'1' => '是'
		];

		if ($result) {
			$page = $result -> render();
			$this -> assign(
				[
					'name'                   => $userName,
					'adminImage'             => $adminImage,
					'commidityInfo'          => $result,
					'commidityTypeIdAndName' => $commidityTypeIdAndName,
					'page'                   => $page,
					'isNew'                  => $isNew,
				]
			);
			return $this -> fetch("commidity");
		} else {
			return $this -> error("暂无数据):");
		}
	}

	//根据ID删除商品
	public function deleteCommidityById($id)
	{
		$commidity = new Commidity;
		$res = $commidity -> deleteCommidityById($id);

		if ($res) {
			$this -> success("删除商品成功！");
		} else {
			$this -> error("删除商品失败！");
		}
	}

	//根据Id修改商品信息
	public function modifyCommidityById($id)
	{
		$login = new Login();
		$userId = $login -> getUserSessionInfo()['id'];

		$commidityName = input('post.commidityName');
		$commidityTypeId = input('post.commidityTypeId');
		$commidityStocks = input('post.commidityStocks');
        $commidityPrice = input('post.commidityPrice');
        $commidityDiscount = input('post.commidityDiscount');
		
    	$data = [
			'name'              => $commidityName,
			'commidity_type_id' => $commidityTypeId,
			'stocks'            => $commidityStocks,
			'price'             => $commidityPrice,
			'discount'          => $commidityDiscount,
			'update_by'         => $userId,
			'update_time'       => time(),
    	];

    	$commidity = new Commidity;
		$res = $commidity -> updateCommidityById($id, $data);

		if ($res) {
			return $this -> success('修改商品信息成功！');
		} else {
			return $this -> error('修改商品信息失败！');
		}		
	}

	//添加商品
	public function addCommidity()
	{
		$login = new Login();
		$userId = $login -> getUserSessionInfo()['id'];

		$commidityName         = input('post.commidityName');
		$commidityTypeId       = input('post.commidityTypeId');
		$commidityPictures     = input('post.commidityPictures');
		$commidityImage        = input('post.commidityImage');
		$commidityIntroduction = input('post.commidityIntroduction');
		$commidityStocks       = input('post.commidityStocks');
		$commidityPrice        = input('post.commidityPrice');
		$commidityDiscount     = input('post.commidityDiscount');
		$commidityIsNew        = input('post.commidityIsNew');
		$commidityExtraInfo    = input('post.commidityExtraInfo');

		//修改上传图片路径为想要的JSON格式
		$commidityPictures = json_decode($commidityPictures, true);
		foreach ($commidityPictures as $key => $value) {
			$commidityPicture[$key + 1] = $value;
		}
		$commidityPicture = json_encode($commidityPicture);
		$validateData = [
			'name'         => $commidityName,
			'introduction' => $commidityIntroduction,
			'stocks'       => $commidityStocks,
			'price'        => $commidityPrice,
			'discount'     => $commidityDiscount,
		];

		$validate = Loader::validate('Commidity');
		if (! $validate -> check($validateData)) {
			return $this -> error($validate -> getError());
		} else {
			$data = [
				'name'              => $commidityName,
				'commidity_type_id' => $commidityTypeId,
				'picture'           => $commidityPicture,
				'image'             => $commidityImage,
				'introduction'      => $commidityIntroduction,
				'stocks'            => $commidityStocks,
				'price'             => $commidityPrice,
				'discount'          => $commidityDiscount,
				'is_new'            => $commidityIsNew,
				'extra_info'        => $commidityExtraInfo,
				'create_by'         => $userId,
				'create_time'       => time(),
			];

			$commidity = new Commidity;
			$res = $commidity -> addCommidity($data);
			if ($res) {
				return $this -> success('添加商品成功！');
			} else {
				return $this -> error('添加商品失败！');
			}
		}
	}

	//根据Ids批量删除商品
	public function deleteCommidityByIds()
	{
		//获取选择的商品
		$commidityIds = json_decode(input('post.commidityIds'), true);
		if (empty($commidityIds)) {
			return $this -> error("请选择要删除的商品！！！");
		} else {
			$commidity = new Commidity;
			$result = $commidity -> deleteCommidityByIds($commidityIds);

			if ($result) {
				$commidityIds = implode(',', $commidityIds);
				return $this -> success('删除 ' . $commidityIds . ' 成功！');
			} else {
				return $this -> error("删除失败！" . $commidity -> getLastSql());
			}
		}
	}

	//根据搜索条件查询商品
	public function searchCommidity()
	{
		//查询条件
		$condition = input('post.condition');
		if (empty($condition)) {
			return $this -> error("请输入要查询的条件！！");
		} else {
			//获取商品类型ＩＤ和名称
			$commidityType = new CommidityType;
			$commidityTypeIdAndName = $commidityType -> getCommidityTypeIdAndName();

			$isNew = [
				"0" => "否",
				"1" => "是"
			];

			$commidity = new Commidity;
			$result = $commidity -> searchCommidity($condition);
			$data['commidityTypeIdAndName'] = $commidityTypeIdAndName;
			$data['data'] = $result;
			$data['isNew'] = $isNew;

			if ($result) {
				return $data;
			} else {
				return $this -> error("暂无数据):");
			}
		}
	}

	//添加商品页面
	public function addPage()
	{
		$login = new Login();
		$userName = $login -> getUserSessionInfo()['name'];
		$userId = $login -> getUserSessionInfo()['id'];
		$adminImage = $login -> getUserSessionInfo()['image'];

		//获取商品类型ＩＤ和名称
		$commidityType = new CommidityType;
		$commidityTypeIdAndName = $commidityType -> getCommidityTypeIdAndName();

		$this -> assign(
			[
				'name'                   => $userName,
				'adminImage'             => $adminImage,
				'commidityTypeIdAndName' => $commidityTypeIdAndName,
			]
		);

		return $this -> fetch("addCommidity");
	}

}