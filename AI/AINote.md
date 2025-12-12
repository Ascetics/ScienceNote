# 数据标注 Data Label

## 以“StructBERT自然语言推理-中文-通用-base”为例

模型地址https://modelscope.cn/models/iic/nlp_structbert_nli_chinese-base

部署Anaconda虚拟环境，下载完整模型。
```shell
conda create -n modelscope python==3.11    # 创建虚拟环境
conda activate modelscope                  # 激活虚拟环境
pip install modelscope                     # 安装modelscope
pip3 install torch torchvision torchaudio  # 安装PyTorch
modelscope download --model iic/nlp_structbert_nli_chinese-base # 下载完整模型库
```

运行Demo
```python
from modelscope.pipelines import pipeline
from modelscope.utils.constant import Tasks

semantic_cls = pipeline(Tasks.nli, 'iic/nlp_structbert_nli_chinese-base', model_revision='master')
semantic_cls(input=('一月份跟二月份肯定有一个月份有.', '肯定有一个月份有'))
```

## 以“百川2-13B-对话模型”为例

模型地址https://modelscope.cn/models/baichuan-inc/Baichuan2-13B-Chat  

下载完整模型
```shell
modelscope download --model baichuan-inc/Baichuan2-13B-Chat # 下载完整模型库
```